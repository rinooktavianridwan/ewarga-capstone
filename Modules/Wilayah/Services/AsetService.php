<?php

namespace Modules\Wilayah\Services;

use Modules\Wilayah\Entities\Aset;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AsetService
{
    public function getAll()
    {
        return Aset::with(['instansi', 'warga', 'jenisAset', 'fotos', 'asetPenghunis'])->get();
    }

    public function getById(int $id): Aset
    {
        $aset = Aset::with(['instansi', 'warga', 'jenisAset', 'fotos', 'asetPenghunis'])->find($id);

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
                foreach ($fotoFiles as $file) {
                    if ($file instanceof UploadedFile) {
                        $instansiId = $data['instansi_id'];
                        $filename = $data['warga_id'] . '_' . time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs("aset_foto/{$instansiId}", $filename, 'public');

                        $aset->fotos()->create([
                            'nama' => $file->getClientOriginalName(),
                            'file_path' => "aset_foto/{$instansiId}/{$filename}",
                        ]);
                    }
                }
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
                $fotos = $aset->fotos()->whereIn('id', $hapusFotoIds)->get();

                foreach ($fotos as $foto) {
                    if (Storage::disk('public')->exists($foto->file_path)) {
                        Storage::disk('public')->delete($foto->file_path);
                    }

                    $foto->delete();
                }
            }

            if (!empty($fotoBaru)) {
                foreach ($fotoBaru as $file) {
                    if ($file instanceof UploadedFile) {
                        $instansiId = $aset->instansi_id;
                        $filename = $aset->warga_id . '_' . time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs("aset_foto/{$instansiId}", $filename, 'public');

                        $aset->fotos()->create([
                            'nama' => $file->getClientOriginalName(),
                            'file_path' => "aset_foto/{$instansiId}/{$filename}",
                        ]);
                    }
                }
            }

            return $aset->load(['fotos']);
        });
    }

    public function delete(Aset $aset): bool|null
    {
        return $aset->delete();
    }

    public function destroy(Aset $aset): bool
    {
        return DB::transaction(function () use ($aset) {
            $fotos = $aset->fotos;

            foreach ($fotos as $foto) {
                if (Storage::disk('public')->exists($foto->file_path)) {
                    Storage::disk('public')->delete($foto->file_path);
                }

                $foto->delete();
            }

            return $aset->delete();
        });
    }
}
