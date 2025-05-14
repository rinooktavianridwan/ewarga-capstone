<?php

namespace Modules\Umkm\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Entities\Umkm;
use Modules\Umkm\Entities\UmkmFoto;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmProdukFoto;

class UmkmFotoService
{
    public function getAll()
    {
        $umkmFotos = UmkmFoto::with('umkm')->get();
        $produkFotos = UmkmProdukFoto::with('umkmProduk')->get();

        if ($umkmFotos->isEmpty() && $produkFotos->isEmpty()) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return [
            'umkm' => $umkmFotos,
            'produk' => $produkFotos,
        ];
    }

    public function getAllByModel(Model $model)
    {
        $fotos = $model->fotos()->get();

        if ($fotos->isEmpty()) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return $fotos;
    }

    public function getById(int $id, Model $model): Model
    {
        if ($model instanceof Umkm) {
            $foto = UmkmFoto::with('umkm')->find($id);
        } elseif ($model instanceof UmkmProduk) {
            $foto = UmkmProdukFoto::with('produk')->find($id);
        } else {
            throw new \InvalidArgumentException("Model tidak dikenali");
        }

        if (!$foto) {
            throw new ModelNotFoundException("Data foto tidak ditemukan");
        }

        return $foto;
    }

    public function store(Model $model, array $fotoFiles, int $instansiId)
    {
        foreach ($fotoFiles as $file) {
            if ($file instanceof UploadedFile) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();

                if ($model instanceof Umkm) {
                    $directory = "umkm_foto/{$instansiId}";
                } elseif ($model instanceof UmkmProduk) {
                    $directory = "produk_foto/{$instansiId}";
                } else {
                    throw new \InvalidArgumentException('Model tidak didukung untuk penyimpanan foto');
                }

                $file->storeAs($directory, $filename, 'public');

                $model->fotos()->create([
                    'nama' => $file->getClientOriginalName(),
                    'file_path' => "{$directory}/{$filename}",
                ]);
            }
        }
    }

    public function delete(Model $model, array $hapusFotoIds)
    {
        $fotos = $model->fotos()->whereIn('id', $hapusFotoIds)->get();

        foreach ($fotos as $foto) {
            if (Storage::disk('public')->exists($foto->file_path)) {
                Storage::disk('public')->delete($foto->file_path);
            }

            $foto->delete();
        }
    }
}
