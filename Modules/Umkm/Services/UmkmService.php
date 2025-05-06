<?php

namespace Modules\Umkm\Services;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\{Umkm, UmkmAlamat, UmkmKontak, UmkmFoto};

class UmkmService
{
    public function getUmkmDetailById($id)
    {
        $umkm = Umkm::with([
            'instansi',
            'umkmKontak',
            'umkmAlamat',
            'umkmFoto',
            'warga',
            'umkmBentukUsaha',
            'umkmJenisUsaha',
        ])->findOrFail($id);

        $formattedAlamat = collect($umkm->umkmAlamat)->map(function ($alamat) {
            $point = DB::selectOne("SELECT ST_X(alamat) AS longitude, ST_Y(alamat) AS latitude FROM umkm_alamat WHERE id = ?", [$alamat->id]);
            return [
                'id' => $alamat->id,
                'umkm_id' => $alamat->umkm_id,
                'latitude' => $point->latitude,
                'longitude' => $point->longitude,
                'created_at' => $alamat->created_at,
                'updated_at' => $alamat->updated_at,
                'deleted_at' => $alamat->deleted_at,
            ];
        });

        return [
            'id' => $umkm->id,
            'nama' => $umkm->nama,
            'keterangan' => $umkm->keterangan,
            'instansi' => $umkm->instansi,
            'kontak' => $umkm->umkmKontak,
            'fotos' => $umkm->umkmFoto,
            'wargas' => $umkm->warga,
            'bentuk_usaha' => $umkm->umkmBentukUsaha,
            'jenis_usaha' => $umkm->umkmJenisUsaha,
            'alamat' => $formattedAlamat,
        ];
    }

    public function getFilteredUmkm(Request $request)
    {
        if (!$request->filled('instansi_id')) {
            abort(400, 'instansi_id wajib dikirim');
        }

        $instansiId = $request->instansi_id;
        $user = auth()->user();

        $hasAccess = Warga::where('user_id', $user->id)
            ->where('instansi_id', $instansiId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke instansi ini.');
        }

        $query = Umkm::with(['umkmBentukUsaha', 'umkmJenisUsaha', 'umkmFoto'])
            ->where('instansi_id', $instansiId);

        if ($request->filled('jenis_usaha_id')) {
            $query->where('umkm_M_jenis_id', $request->jenis_usaha_id);
        }

        if ($request->filled('bentuk_usaha_id')) {
            $query->where('umkm_M_bentuk_id', $request->bentuk_usaha_id);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return $query->get();
    }


    public function createUmkm(array $data,  ?array $fotoFiles = null)
    {
        return DB::transaction(function () use ($data, $fotoFiles) {
            $umkm = Umkm::create([
                'nama' => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
                'instansi_id' => $data['instansi_id'] ?? null,
                'umkm_M_bentuk_id' => $data['bentuk_usaha_id'] ?? null,
                'umkm_M_jenis_id' => $data['jenis_usaha_id'] ?? null,
            ]);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                $umkm->warga()->attach($data['warga_ids']);
            }

            if (!empty($data['kontak']) && is_array($data['kontak'])) {
                foreach ($data['kontak'] as $kontak) {
                    UmkmKontak::create([
                        'umkm_id' => $umkm->id,
                        'kontak' => $kontak,
                    ]);
                }
            }

            if (!empty($data['alamat']) && is_array($data['alamat'])) {
                foreach ($data['alamat'] as $item) {
                    if (!empty($item['latitude']) && !empty($item['longitude'])) {
                        UmkmAlamat::create([
                            'umkm_id' => $umkm->id,
                            'alamat' => DB::raw("ST_GeomFromText('POINT({$item['longitude']} {$item['latitude']})')"),
                        ]);
                    }
                }
            }

            if ($fotoFiles) {
                $this->handleFotoUpload($umkm, $fotoFiles);
            }

            return $umkm->load(['umkmBentukUsaha', 'umkmJenisUsaha', 'warga', 'umkmFoto']);
        });
    }

    public function updateUmkm($id, array $data, $fotoFiles = null)
    {
        return DB::transaction(function () use ($id, $data, $fotoFiles) {
            $umkm = Umkm::findOrFail($id);
            $user = auth()->user();

            $instansiIdToCheck = $data['instansi_id'] ?? $umkm->instansi_id;

            $hasAccess = Warga::where('user_id', $user->id)
                ->where('instansi_id', $instansiIdToCheck)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terikat dengan instansi tersebut.'
                ], 403);
            }

            $umkm->update([
                'nama' => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
                'instansi_id' => $instansiIdToCheck,
                'umkm_M_bentuk_id' => $data['bentuk_usaha_id'] ?? $umkm->umkm_M_bentuk_id,
                'umkm_M_jenis_id' => $data['jenis_usaha_id'] ?? $umkm->umkm_M_jenis_id,
            ]);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                $umkm->warga()->sync($data['warga_ids']);
            }

            if ($fotoFiles && is_array($fotoFiles)) {
                $this->syncFotos($umkm, $fotoFiles);
            }

            return $umkm->fresh(['umkmBentukUsaha', 'umkmJenisUsaha', 'warga', 'umkmFoto']);
        });
    }


    public function deleteUmkm($id)
    {
        $umkm = Umkm::with([
            'umkmProduk.umkmProdukFoto',
            'umkmKontak',
            'umkmAlamat',
            'umkmFoto',
            'warga',
        ])->findOrFail($id);

        DB::transaction(function () use ($umkm) {
            foreach ($umkm->umkmProduk as $produk) {
                $produk->umkmProdukFoto()->delete();
            }

            $umkm->umkmProduk()->delete();
            $umkm->umkmKontak()->delete();
            $umkm->umkmAlamat()->delete();
            $umkm->umkmFoto()->delete();
            $umkm->warga()->detach();
            $umkm->delete();
        });
    }

    protected function handleFotoUpload(Umkm $umkm, array $fotoFiles): void
    {
        foreach ($fotoFiles as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'umkm_foto/' . $filename;
            $file->storeAs('public', $path);

            UmkmFoto::create([
                'umkm_id' => $umkm->id,
                'nama' => $path,
            ]);
        }
    }

    protected function syncFotos(Umkm $umkm, array $fotoFiles): void
    {
        $fotoBaruHashes = [];
        $fileMap = [];

        foreach ($fotoFiles as $file) {
            $hash = md5_file($file->getRealPath());
            $fotoBaruHashes[] = $hash;
            $fileMap[$hash] = $file;
        }

        $fotoAktif = $umkm->umkmFoto()->get();
        $fotoSemua = $umkm->umkmFoto()->withTrashed()->get();

        $hashFotoLama = [];
        foreach ($fotoSemua as $foto) {
            $path = storage_path("app/public/{$foto->nama}");
            if (file_exists($path)) {
                $hashFotoLama[md5_file($path)] = $foto;
            }
        }

        $fotoBaruUntukUpload = [];

        foreach ($fotoBaruHashes as $hash) {
            if (isset($hashFotoLama[$hash])) {
                $foto = $hashFotoLama[$hash];
                if ($foto->trashed()) {
                    $foto->restore();
                }
            } else {
                $fotoBaruUntukUpload[] = $fileMap[$hash];
            }
        }

        foreach ($fotoAktif as $foto) {
            $path = storage_path("app/public/{$foto->nama}");
            if (file_exists($path)) {
                $hash = md5_file($path);
                if (!in_array($hash, $fotoBaruHashes)) {
                    $foto->delete();
                }
            }
        }

        if (count($fotoBaruUntukUpload) > 0) {
            if (count($fotoBaruUntukUpload) > 5) {
                throw new \Exception("Maksimal 5 foto diperbolehkan untuk satu UMKM.");
            }

            $this->handleFotoUpload($umkm, $fotoBaruUntukUpload);
        }
    }

    public function createUmkmWithValidation(array $data, $fotoFiles)
    {
        if (!empty($fotoFiles) && count($fotoFiles) > 5) {
            abort(422, 'Foto yang dikirim tidak boleh lebih dari 5.');
        }

        $this->validateWargaInstansi($data);

        return $this->createUmkm($data, $fotoFiles);
    }

    public function updateUmkmWithValidation($id, array $data, $fotoFiles = null)
    {
        if (!empty($fotoFiles) && count($fotoFiles) > 5) {
            abort(422, 'Foto yang dikirim tidak boleh lebih dari 5.');
        }

        if (isset($data['instansi_id']) && isset($data['warga_ids'])) {
            $this->validateWargaInstansi($data);
        }

        return $this->updateUmkm($id, $data, $fotoFiles);
    }

    protected function validateWargaInstansi(array $data): void
    {
        if (!isset($data['instansi_id']) || !isset($data['warga_ids'])) {
            return;
        }

        $instansiId = $data['instansi_id'];
        $wargaIds = $data['warga_ids'];

        $invalidWarga = Warga::whereIn('id', $wargaIds)
            ->where(function ($query) use ($instansiId) {
                $query->whereNull('instansi_id')
                    ->orWhere('instansi_id', '!=', $instansiId);
            })
            ->exists();

        if ($invalidWarga) {
            abort(400, 'Semua warga harus memiliki instansi_id yang sama dengan instansi_id yang dipilih.');
        }
    }
}
