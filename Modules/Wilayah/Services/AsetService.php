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
        return Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])->get();
    }

    public function getAllByName(string $name)
    {
        return Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])
            ->where('nama', 'like', '%' . $name . '%')
            ->get();
    }

    public function getById(int $id): Aset
    {
        $aset = Aset::with(['instansi', 'warga', 'jenis', 'fotos', 'asetPenghunis'])->find($id);

        if (!$aset) {
            throw new ModelNotFoundException("Aset dengan ID {$id} tidak ditemukan.");
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

            return $aset->load(['fotos']);
        });
    }

    public function update(Aset $aset, array $data): Aset
    {
        return DB::transaction(function () use ($aset, $data) {
            $fotoBaru = $data['fotos'] ?? [];
            $hapusFotoIds = $data['hapus_foto'] ?? [];

            unset($data['fotos'], $data['hapus_foto']);

            $aset->update($data);

            if (!empty($hapusFotoIds)) {
                $this->asetFotoService->delete($aset, $hapusFotoIds);
            }

            if (!empty($fotoBaru)) {
                $this->asetFotoService->store($aset, $fotoBaru, $aset->instansi_id, $aset->warga_id);
            }

            return $aset->load(['fotos']);
        });
    }

    public function delete(Aset $aset): bool
    {
        return DB::transaction(function () use ($aset) {
            $this->asetFotoService->delete($aset, $aset->fotos->pluck('id')->toArray());

            return $aset->delete();
        });
    }

    public function getLokasi(Aset $aset): ?array
    {
        if (!$aset->lokasi) {
            throw new ModelNotFoundException("Lokasi untuk Aset dengan ID {$aset->id} tidak ditemukan.");
        }

        return [
            'latitude' => $aset->lokasi->getLat(),
            'longitude' => $aset->lokasi->getLng(),
        ];
    }

    public function updateLokasi(Aset $aset, float $latitude, float $longitude): Aset
    {
        $point = DB::raw("ST_GeomFromText('POINT({$longitude} {$latitude})')");

        $aset->update([
            'lokasi' => $point,
        ]);

        return $aset;
    }
}
