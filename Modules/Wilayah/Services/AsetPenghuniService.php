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
        $data = AsetPenghuni::with(['aset', 'warga', 'status'])->get();
        if ($data->isEmpty()) {
            throw new ModelNotFoundException("Data penghuni tidak ditemukan");
        }
        return $data;
    }

    public function getAllByAset(Aset $aset)
    {
        $data = $aset->asetPenghunis()->with(['warga', 'status'])->get();
        if ($data->isEmpty()) {
            throw new ModelNotFoundException("Data penghuni tidak ditemukan");
        }
        return $data;
    }

    public function getById(int $id): AsetPenghuni
    {
        $penghuni = AsetPenghuni::with(['aset', 'warga', 'status'])->find($id);
        if (!$penghuni) {
            throw new ModelNotFoundException("Data penghuni tidak ditemukan");
        }
        return $penghuni;
    }

    public function store(Aset $aset, array $penghuniData): array
    {
        $created = [];

        DB::transaction(function () use ($aset, $penghuniData, &$created) {
            foreach ($penghuniData as $data) {
                $existing = AsetPenghuni::withTrashed()
                    ->where('aset_id', $aset->id)
                    ->where('warga_id', $data['warga_id'])
                    ->first();

                if ($existing) {
                    if ($existing->trashed()) {
                        $existing->restore();
                        $existing->update([
                            'aset_m_status_id' => $data['aset_m_status_id'],
                        ]);
                        $created[] = $existing;
                    } else {
                        throw new \Exception("Penghuni tidak boleh duplikat");
                    }
                } else {
                    $created[] = $aset->asetPenghunis()->create([
                        'warga_id' => $data['warga_id'],
                        'aset_m_status_id' => $data['aset_m_status_id'],
                    ]);
                }
            }
        });

        return $created;
    }

    public function update(Aset $aset, array $penghuniData): array
    {
        return DB::transaction(function () use ($aset, $penghuniData) {
            $penghuniLama = $aset->asetPenghunis()->get();
            $penghuniLamaIds = $penghuniLama->pluck('warga_id')->toArray();

            $penghuniBaruIds = collect($penghuniData)->pluck('warga_id')->toArray();

            $hapusPenghuni = $penghuniLama->filter(function ($item) use ($penghuniBaruIds) {
                return !in_array($item->warga_id, $penghuniBaruIds);
            });

            foreach ($hapusPenghuni as $penghuni) {
                $this->delete($aset, [$penghuni->id]);
            }

            $tambahPenghuni = collect($penghuniData)->filter(function ($item) use ($penghuniLamaIds) {
                return !in_array($item['warga_id'], $penghuniLamaIds);
            });

            $created = [];
            foreach ($tambahPenghuni as $data) {
                $created[] = $aset->asetPenghunis()->create([
                    'warga_id' => $data['warga_id'],
                    'aset_m_status_id' => $data['aset_m_status_id'],
                ]);
            }

            $updatePenghuni = collect($penghuniData)->filter(function ($item) use ($penghuniLamaIds) {
                return in_array($item['warga_id'], $penghuniLamaIds);
            });

            foreach ($updatePenghuni as $data) {
                $penghuni = $penghuniLama->firstWhere('warga_id', $data['warga_id']);
                if ($penghuni && $penghuni->aset_m_status_id != $data['aset_m_status_id']) {
                    $penghuni->update([
                        'aset_m_status_id' => $data['aset_m_status_id'],
                    ]);
                }
            }

            return ["penghuni" => $aset->asetPenghunis()->get(['warga_id', 'aset_m_status_id'])->toArray()];
        });
    }

    public function delete(Aset $aset, array $hapusPenghuniIds)
    {
        $penghunis = $aset->asetPenghunis()->whereIn('id', $hapusPenghuniIds)->get();

        if ($penghunis->isEmpty()) {
            throw new ModelNotFoundException("Tidak ada penghuni yang ditemukan untuk ID yang diberikan.");
        }

        foreach ($penghunis as $penghuni) {
            $penghuni->delete();
        }

        return count($penghunis);
    }
}
