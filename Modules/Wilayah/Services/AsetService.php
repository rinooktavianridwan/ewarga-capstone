<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Services\AsetFotoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsetService
{
    protected $asetFotoService;

    public function __construct(AsetFotoService $asetFotoService)
    {
        $this->asetFotoService = $asetFotoService;
    }

    public function getAll()
    {
        $aset = Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])->get();

        if ($aset->isEmpty()) {
            throw new ModelNotFoundException("Data aset tidak ditemukan");
        }

        return $aset;
    }

    public function getAllByName(string $name)
    {
        $aset = Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])
            ->where('nama', 'like', '%' . $name . '%')
            ->get();

        if ($aset->isEmpty()) {
            throw new ModelNotFoundException("Aset dengan nama {$name} tidak ditemukan");
        }

        return $aset;
    }

    public function getAllByInstansi(int $instansiId)
    {
        $aset = Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])
            ->where('instansi_id', $instansiId)
            ->get();

        if ($aset->isEmpty()) {
            throw new ModelNotFoundException("Data aset tidak ditemukan");
        }

        return $aset;
    }

    public function getById(int $id): Aset
    {
        $aset = Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])->find($id);

        if (!$aset) {
            throw new ModelNotFoundException("Data aset tidak ditemukan");
        }

        return $aset;
    }

    public function create(array $data): Aset
    {
        return DB::transaction(function () use ($data) {
            $fotoFiles = $data['fotos'] ?? [];
            unset($data['fotos']);

            $aset = Aset::create($data);

            if (!empty($fotoFiles)) {
                $this->asetFotoService->store($aset, $fotoFiles, $data['instansi_id'], $data['warga_id']);
            }

            return $aset->load(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis']);
        });
    }

    public function update(Aset $aset, array $data): Aset
    {
        return DB::transaction(function () use ($aset, $data) {
            $fotoBaru = $data['fotos'] ?? [];
            $hapusFotoIds = $data['hapus_foto'] ?? [];

            unset($data['fotos'], $data['hapus_foto']);

            $fotoTersisa = $aset->fotos->count() - count($hapusFotoIds);

            if (($fotoTersisa + count($fotoBaru)) > 5) {
                throw new \Exception('Jumlah total foto tidak boleh lebih dari 5');
            }

            $aset->update($data);

            if (!empty($hapusFotoIds)) {
                $this->asetFotoService->delete($aset, $hapusFotoIds);
            }

            if (!empty($fotoBaru)) {
                $this->asetFotoService->store($aset, $fotoBaru, $aset->instansi_id, $aset->warga_id);
            }

            return $aset->load(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis']);
        });
    }

    public function delete(Aset $aset): Aset
    {
        return DB::transaction(function () use ($aset) {
            $this->asetFotoService->delete($aset, $aset->fotos->pluck('id')->toArray());

            $deletedAset = $aset->replicate();
            $aset->delete();

            return $deletedAset;
        });
    }

    public function getLokasi(Aset $aset): ?array
    {
        if (is_null($aset->latitude) || is_null($aset->longitude)) {
            throw new ModelNotFoundException("Lokasi aset tidak ditemukan.");
        }

        return [
            'latitude' => $aset->latitude,
            'longitude' => $aset->longitude,
        ];
    }

    public function updateLokasi(Aset $aset, float $latitude, float $longitude): Aset
    {
        $point = DB::raw("ST_GeomFromText('POINT({$longitude} {$latitude})')");

        $aset->update([
            'lokasi' => $point,
        ]);

        $aset = Aset::find($aset->id);

        return $aset;
    }
}
