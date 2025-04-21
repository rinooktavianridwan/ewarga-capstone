<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\Aset;
use Modules\Wilayah\Entities\AsetPenghuni;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AsetPenghuniService
{
    public function getAll()
    {
        return AsetPenghuni::with(['aset', 'warga', 'status'])->get();
    }

    public function getAllByAset(Aset $aset)
    {
        return $aset->asetPenghunis()->with(['warga', 'status'])->get();
    }
    
    public function getById(int $id): AsetPenghuni
    {
        $penghuni = AsetPenghuni::with(['aset', 'warga', 'status'])->find($id);

        if (!$penghuni) {
            throw new ModelNotFoundException("Penghuni dengan ID {$id} tidak ditemukan.");
        }

        return $penghuni;
    }

    public function store(Aset $aset, array $penghuniData): array
    {
        $created = [];

        DB::transaction(function () use ($aset, $penghuniData, &$created) {
            foreach ($penghuniData as $data) {
                $created[] = $aset->asetPenghunis()->create([
                    'warga_id' => $data['warga_id'],
                    'aset_m_status_id' => $data['aset_m_status_id'],
                ]);
            }
        });

        return $created;
    }

    public function delete(int $id): bool
    {
        $penghuni = AsetPenghuni::find($id);

        if (!$penghuni) {
            throw new ModelNotFoundException("Penghuni dengan ID {$id} tidak ditemukan.");
        }

        return $penghuni->delete();
    }
}
