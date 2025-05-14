<?php

namespace Modules\Umkm\Services;

use App\Services\WargaService;
use App\Exceptions\FlowException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Modules\Umkm\Entities\{Umkm, UmkmKontak};
use Modules\Umkm\Services\UmkmFotoService;

class UmkmService
{
    protected $wargaService;
    protected $umkmFotoService;

    public function __construct(WargaService $wargaService, UmkmFotoService $umkmFotoService)
    {
        $this->umkmFotoService = $umkmFotoService;
        $this->wargaService = $wargaService;
    }

    public function getFiltered(array $data)
    {
        $instansiId = $data['instansi_id'];
        $userId = auth()->user()->id;

        $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

        if (!$hasAccess) {
            throw new FlowException("Pengguna tidak memiliki akses ke instansi ini");
        }

        $umkm = Umkm::with(['instansi', 'bentuk', 'jenis', 'fotos', 'umkmWargas', 'produks', 'kontaks'])
            ->where('instansi_id', $instansiId);


        if (!empty($data['jenis'])) {
            $jenisIds = is_array($data['jenis']) ? $data['jenis'] : explode(',', $data['jenis']);
            $umkm->whereIn('umkm_m_jenis_id', $jenisIds);
        }

        if (!empty($data['bentuk'])) {
            $jenisIds = is_array($data['bentuk']) ? $data['bentuk'] : explode(',', $data['bentuk']);
            $umkm->whereIn('umkm_m_bentuk_id', $jenisIds);
        }

        if (!empty($data['nama'])) {
            $umkm->where('nama', 'like', '%' . $data['nama'] . '%');
        }

        $umkm = $umkm->get();

        if ($umkm->isEmpty()) {
            throw new ModelNotFoundException("Data umkm tidak ditemukan");
        }

        return $umkm;
    }

    public function getById($id): Umkm
    {
        $umkm = Umkm::with([
            'instansi',
            'jenis',
            'bentuk',
            'umkmWargas',
            'produks',
            'kontaks',
            'fotos',
        ])->find($id);

        if (!$umkm) {
            throw new ModelNotFoundException("Data umkm tidak ditemukan");
        }

        return $umkm;
    }

    public function create(array $data): Umkm
    {
        $instansiId = $data['instansi_id'];
        $userId = auth()->user()->id;

        $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

        if (!$hasAccess) {
            throw new FlowException("Pengguna tidak memiliki akses ke instansi ini");
        }

        $fotoFiles = $data['fotos'] ?? [];
        unset($data['fotos']);

        return DB::transaction(function () use ($data, $fotoFiles) {
            $data['lokasi'] = DB::raw("ST_GeomFromText('POINT({$data['lokasi'][0]['longitude']} {$data['lokasi'][0]['latitude']})')");

            $umkm = Umkm::create($data);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                foreach ($data['warga_ids'] as $wargaId) {
                    $existing = $umkm->umkmWargas()->withTrashed()
                        ->where('warga_id', $wargaId)
                        ->first();

                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        } else {
                            throw new \Exception("Pemilik tidak boleh duplikat");
                        }
                    } else {
                        $umkm->umkmWargas()->create([
                            'warga_id' => $wargaId
                        ]);
                    }
                }
            }

            if (!empty($data['kontak']) && is_array($data['kontak'])) {
                foreach ($data['kontak'] as $kontakItem) {
                    $existing = UmkmKontak::withTrashed()
                        ->where('umkm_id', $umkm->id)
                        ->where('umkm_m_kontak_id', $kontakItem['umkm_m_kontak_id'])
                        ->where('kontak', $kontakItem['kontak'])
                        ->first();

                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        } else {
                            throw new \Exception("Kontak tidak boleh duplikat");
                        }
                    } else {
                        UmkmKontak::create([
                            'umkm_id' => $umkm->id,
                            'umkm_m_kontak_id' => $kontakItem['umkm_m_kontak_id'],
                            'kontak' => $kontakItem['kontak'],
                        ]);
                    }
                }
            }

            if (!empty($fotoFiles)) {
                $this->umkmFotoService->store($umkm, $fotoFiles, $data['instansi_id']);
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

    public function update(Umkm $umkm, array $data): Umkm
    {
        $instansiId = $data['instansi_id'];
        $userId = auth()->user()->id;

        if (!$this->isOwner($umkm, $userId)) {
            $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

            if (!$hasAccess) {
                throw new FlowException("Pengguna tidak memiliki izin untuk mengubah umkm ini");
            }
        }

        return DB::transaction(function () use ($umkm, $data) {
            $fotoBaru = $data['fotos'] ?? [];
            $hapusFotoIds = $data['hapus_foto'] ?? [];

            unset($data['fotos'], $data['hapus_foto']);

            $updateData = $data;

            $fotoTersisa = $umkm->fotos->count() - count($hapusFotoIds);

            if (($fotoTersisa + count($fotoBaru)) > 5) {
                throw new \Exception('Jumlah total foto tidak boleh lebih dari 5');
            }

            if (!empty($data['lokasi'][0]['longitude']) && !empty($data['lokasi'][0]['latitude'])) {
                $updateData['lokasi'] = DB::raw("ST_GeomFromText('POINT({$data['lokasi'][0]['longitude']} {$data['lokasi'][0]['latitude']})')");
            }

            $umkm->update($updateData);

            if (!empty($data['warga_ids']) && is_array($data['warga_ids'])) {
                $wargaBaruIds = array_unique($data['warga_ids']);

                $wargaLama = $umkm->umkmWargas()->withTrashed()->get();

                foreach ($wargaLama as $relasi) {
                    if (!in_array($relasi->warga_id, $wargaBaruIds) && $relasi->deleted_at === null) {
                        $relasi->delete();
                    }
                }

                foreach ($wargaBaruIds as $wargaId) {
                    $relasi = $wargaLama->firstWhere('warga_id', $wargaId);

                    if ($relasi) {
                        if ($relasi->trashed()) {
                            $relasi->restore();
                        }
                    } else {
                        $sudahAda = $umkm->umkmWargas()->withTrashed()->where('warga_id', $wargaId)->exists();
                        if (!$sudahAda) {
                            $umkm->umkmWargas()->create(['warga_id' => $wargaId]);
                        }
                    }
                }
            }

            if (!empty($data['kontak']) && is_array($data['kontak'])) {
                $kontakBaruUnik = collect($data['kontak'])
                    ->unique(fn($item) => $item['umkm_m_kontak_id'] . '|' . $item['kontak'])
                    ->values()
                    ->all();

                $kontakLama = $umkm->kontaks()->withTrashed()->get();

                foreach ($kontakBaruUnik as $kontakItem) {
                    $kontakValue = $kontakItem['kontak'];
                    $jenisId = $kontakItem['umkm_m_kontak_id'];

                    $existing = $kontakLama->first(function ($k) use ($kontakValue, $jenisId) {
                        return $k->kontak === $kontakValue && $k->umkm_m_kontak_id === $jenisId;
                    });

                    if ($existing) {
                        if ($existing->trashed()) {
                            $existing->restore();
                        }
                    } else {
                        UmkmKontak::create([
                            'umkm_id' => $umkm->id,
                            'umkm_m_kontak_id' => $jenisId,
                            'kontak' => $kontakValue,
                        ]);
                    }
                }

                $kombinasiBaru = collect($kontakBaruUnik)
                    ->map(fn($item) => $item['umkm_m_kontak_id'] . '|' . $item['kontak'])
                    ->toArray();

                foreach ($kontakLama as $kontak) {
                    $key = $kontak->umkm_m_kontak_id . '|' . $kontak->kontak;
                    if (!in_array($key, $kombinasiBaru) && !$kontak->trashed()) {
                        $kontak->delete();
                    }
                }
            }

            if (!empty($hapusFotoIds)) {
                $this->umkmFotoService->delete($umkm, $hapusFotoIds);
            }

            if (!empty($fotoBaru)) {
                $this->umkmFotoService->store($umkm, $fotoBaru, $umkm['instansi_id']);
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


    public function delete(Umkm $umkm): array
    {
        return DB::transaction(function () use ($umkm) {
            $this->umkmFotoService->delete($umkm, $umkm->fotos->pluck('id')->toArray());
            $umkmData = $umkm->toArray();
            $umkm->delete();

            return $umkmData;
        });
    }


    public function isOwner(Umkm $umkm, int $userId): bool
    {
        return $umkm->umkmWargas()->whereHas('warga', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->exists();
    }
}
