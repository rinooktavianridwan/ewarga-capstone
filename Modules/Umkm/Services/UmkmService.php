<?php

namespace Modules\Umkm\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Umkm\Entities\{Umkm, UmkmAlamat, UmkmKontak, UmkmFoto};

class UmkmService
{
    public function getUmkmDetailById($id)
    {
        $umkm = Umkm::with([
            'instansi',
            'kontak',
            'alamat',
            'fotos',
            'wargas',
            'bentukUsaha',
            'jenisUsaha',
        ])->findOrFail($id);


        $formattedAlamat = collect($umkm->alamat)->map(function ($alamat) {
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

        $response = [
            'id' => $umkm->id,
            'nama' => $umkm->nama,
            'keterangan' => $umkm->keterangan,
            'instansi' => $umkm->instansi,
            'kontak' => $umkm->kontak,
            'fotos' => $umkm->fotos,
            'wargas' => $umkm->wargas,
            'bentuk_usaha' => $umkm->bentukUsaha,
            'jenis_usaha' => $umkm->jenisUsaha,
            'alamat' => $formattedAlamat,
        ];

        return $response;
    }

    public function getFilteredUmkm(Request $request)
    {
        if (!$request->filled('instansi_id')) {
            abort(400, 'instansi_id wajib dikirim');
        }

        $instansiId = $request->instansi_id;

        $query = Umkm::with(['bentukUsaha', 'jenisUsaha', 'fotos'])
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



    public function createUmkm(array $data, array $fotoFiles)
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
                $umkm->wargas()->attach($data['warga_ids']);
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

            $this->handleFotoUpload($umkm, $fotoFiles);

            return $umkm->load(['bentukUsaha', 'jenisUsaha', 'wargas', 'fotos']);
        });
    }

    public function updateUmkm($id, array $data, $fotoFiles = null)
    {
        return DB::transaction(function () use ($id, $data, $fotoFiles) {
            $umkm = Umkm::findOrFail($id);

            $umkm->update([
                'nama' => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
                'instansi_id' => $data['instansi_id'] ?? $umkm->instansi_id,
                'umkm_M_bentuk_id' => $data['bentuk_usaha_id'] ?? $umkm->umkm_M_bentuk_id,
                'umkm_M_jenis_id' => $data['jenis_usaha_id'] ?? $umkm->umkm_M_jenis_id,
            ]);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                $umkm->wargas()->sync($data['warga_ids']);
            }

            if ($fotoFiles && is_array($fotoFiles)) {
                $fotoBaruHashes = [];
                $fileMap = [];

                foreach ($fotoFiles as $file) {
                    $hash = md5_file($file->getRealPath());
                    $fotoBaruHashes[] = $hash;
                    $fileMap[$hash] = $file;
                }

                $fotoAktif = $umkm->fotos()->get();
                $fotoSemua = $umkm->fotos()->withTrashed()->get();

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
                    if ((count($fotoBaruUntukUpload)) > 5) {
                        throw new \Exception("Maksimal 5 foto diperbolehkan untuk satu UMKM.");
                    }

                    $this->handleFotoUpload($umkm, $fotoBaruUntukUpload);
                }
            }

            return $umkm->fresh(['bentukUsaha', 'jenisUsaha', 'wargas', 'fotos']);
        });
    }

    public function deleteUmkm($id)
    {
        $umkm = Umkm::with([
            'produks.fotos',
            'kontak',
            'alamat',
            'fotos',
            'wargas',
        ])->findOrFail($id);

        DB::transaction(function () use ($umkm) {
            foreach ($umkm->produks as $produk) {
                $produk->fotos()->delete(); // delete semua foto dari produk tersebut
            }

            $umkm->produks()->delete();

            $umkm->kontak()->delete();
            $umkm->alamat()->delete();
            $umkm->fotos()->delete();
            $umkm->wargas()->detach();
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
}
