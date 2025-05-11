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
            'jenis',
            'bentuk',
            'umkmWargas',
            'produks',
            'kontaks',
            'fotos',
        ])->findOrFail($id);

        return $umkm;
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

        $query = Umkm::with(['instansi', 'bentuk', 'jenis', 'fotos', 'umkmWargas', 'produks', 'kontaks'])
            ->where('instansi_id', $instansiId);

        if ($request->filled('jenis')) {
            $query->where('umkm_m_jenis_id', $request->jenis);
        }

        if ($request->filled('bentuk')) {
            $query->where('umkm_m_bentuk_id', $request->bentuk);
        }

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
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
                'umkm_m_bentuk_id' => $data['umkm_m_bentuk_id'] ?? null,
                'umkm_m_jenis_id' => $data['umkm_m_jenis_id'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'lokasi' => DB::raw("ST_GeomFromText('POINT({$data['lokasi'][0]['longitude']} {$data['lokasi'][0]['latitude']})')")
            ]);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                foreach ($data['warga_ids'] as $wargaId) {
                    $umkm->umkmWargas()->create([
                        'warga_id' => $wargaId
                    ]);
                }
            }

            if (!empty($data['kontak']) && is_array($data['kontak'])) {
                foreach ($data['kontak'] as $kontakItem) {
                    UmkmKontak::create([
                        'umkm_id' => $umkm->id,
                        'umkm_m_kontak_id' => $kontakItem['umkm_m_kontak_id'],
                        'kontak' => $kontakItem['kontak'],
                    ]);
                }
            }

            if ($fotoFiles) {
                $this->handleFotoUpload($umkm, $fotoFiles);
            }

            return $umkm->load([
                'instansi',
                'jenis',
                'bentuk',
                'umkmWargas',
                'kontaks',
                'fotos',
            ]);
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
                'umkm_m_bentuk_id' => $data['umkm_m_bentuk_id'] ?? $umkm->umkm_m_bentuk_id,
                'umkm_m_jenis_id' => $data['umkm_m_jenis_id'] ?? $umkm->umkm_m_jenis_id,
                'alamat' => $data['alamat'] ?? null,
                'lokasi' => isset($data['lokasi'][0]['longitude'], $data['lokasi'][0]['latitude'])
                    ? DB::raw("ST_GeomFromText('POINT({$data['lokasi'][0]['longitude']} {$data['lokasi'][0]['latitude']})')")
                    : $umkm->lokasi,

            ]);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                $umkm->umkmWargas()->delete();
                foreach ($data['warga_ids'] as $wargaId) {
                    $umkm->umkmWargas()->create([
                        'warga_id' => $wargaId
                    ]);
                }
            }

            if (!empty($data['kontak']) && is_array($data['kontak'])) {
                $existingKontakIds = collect($data['kontak'])->pluck('id')->filter()->all();

                if (!empty($existingKontakIds)) {
                    $umkm->umkmKontak()->whereNotIn('id', $existingKontakIds)->delete();
                }

                foreach ($data['kontak'] as $kontakItem) {
                    if (!empty($kontakItem['id'])) {
                        $kontak = UmkmKontak::where('umkm_id', $umkm->id)->where('id', $kontakItem['id'])->first();
                        if ($kontak) {
                            $kontak->update([
                                'umkm_m_kontak_id' => $kontakItem['umkm_m_kontak_id'],

                                'kontak' => $kontakItem['kontak'],
                            ]);
                            continue;
                        }
                    }

                    UmkmKontak::create([
                        'umkm_id' => $umkm->id,
                        'umkm_m_kontak_id' => $kontakItem['umkm_m_kontak_id'],
                        'kontak' => $kontakItem['kontak'],
                    ]);
                }
            }

            if ($fotoFiles && is_array($fotoFiles)) {
                $this->syncFotos($umkm, $fotoFiles);
            }

            return $umkm->fresh([
                'instansi',
                'jenis',
                'bentuk',
                'umkmWargas',
                'kontaks',
                'fotos',
            ]);
        });
    }


    public function deleteUmkm($id)
    {
        $umkm = Umkm::with([
            'produks.fotos',
            'kontaks',
            'fotos',
            'umkmWargas',
        ])->findOrFail($id);

        DB::transaction(function () use ($umkm) {
            foreach ($umkm->produks as $produk) {
                $produk->fotos()->delete();
            }

            $umkm->produks()->delete();
            $umkm->kontaks()->delete();
            $umkm->fotos()->delete();
            $umkm->umkmWargas()->delete();
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
