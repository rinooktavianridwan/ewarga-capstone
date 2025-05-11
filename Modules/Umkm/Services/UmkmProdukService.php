<?php

namespace Modules\Umkm\Services;

use App\Models\Warga;
use Illuminate\Http\Request;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmProdukFoto;

class UmkmProdukService
{
    public function getFilteredProduk(Request $request)
    {
        if (!$request->filled('instansi_id')) {
            abort(400, 'instansi_id wajib dikirim');
        }

        if (!$request->filled('umkm_id')) {
            abort(400, 'umkm_id wajib dikirim');
        }

        $instansiId = $request->instansi_id;
        $umkmId = $request->umkm_id;
        $user = auth()->user();

        $hasAccess = Warga::where('user_id', $user->id)
            ->where('instansi_id', $instansiId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke instansi ini.');
        }

        $query = UmkmProduk::with(['umkm', 'instansi', 'umkmProdukFoto'])
            ->where('umkm_id', $umkmId)
            ->where('instansi_id', $instansiId);

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return $query->get();
    }

    public function getById($id)
    {
        return UmkmProduk::with(['umkm', 'instansi', 'umkmProdukFoto'])->findOrFail($id);
    }

    public function store(array $data, array $fotoFiles): UmkmProduk
    {
        $user = auth()->user();

        $hasAccess = Warga::where('user_id', $user->id)
            ->where('instansi_id', $data['instansi_id'])
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Pengguna tidak terikat dengan instansi tersebut.');
        }

        $produk = UmkmProduk::create($data);

        $this->handleFotoUpload($produk, $fotoFiles);

        return $produk->load('umkmProdukFoto');
    }

    public function update($id, array $data, array $fotoFiles = [])
    {
        $produk = UmkmProduk::findOrFail($id);
        $user = auth()->user();

        $hasAccess = Warga::where('user_id', $user->id)
            ->where('instansi_id', $produk->instansi_id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Pengguna tidak terikat dengan instansi tersebut.');
        }

        $produk->update($data);

        if (!empty($fotoFiles)) {
            $this->syncFotos($produk, $fotoFiles);
        }

        return $produk->fresh('umkmProdukFoto');
    }

    public function delete($id): void
    {
        $produk = UmkmProduk::with('umkmProdukFoto')->findOrFail($id);

        foreach ($produk->umkmProdukFoto as $foto) {
            $foto->delete();
        }

        $produk->delete();
    }

    public function restoreFotoProduk($produkId): void
    {
        $produk = UmkmProduk::withTrashed()->findOrFail($produkId);

        UmkmProdukFoto::onlyTrashed()
            ->where('umkm_produk_id', $produk->id)
            ->restore();
    }

    protected function handleFotoUpload(UmkmProduk $produk, array $fotoFiles): void
    {
        foreach ($fotoFiles as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'produk_foto/' . $filename;
            $file->storeAs('public', $path);

            UmkmProdukFoto::create([
                'umkm_produk_id' => $produk->id,
                'nama' => $path,
            ]);
        }
    }

    protected function syncFotos(UmkmProduk $produk, array $fotoFiles): void
    {
        $fotoBaruHashes = [];
        $fileMap = [];

        foreach ($fotoFiles as $file) {
            $hash = md5_file($file->getRealPath());
            $fotoBaruHashes[] = $hash;
            $fileMap[$hash] = $file;
        }

        $fotoAktif = $produk->umkmProdukFoto()->get();
        $fotoSemua = $produk->umkmProdukFoto()->withTrashed()->get();

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
                throw new \Exception("Maksimal 5 foto diperbolehkan untuk satu produk.");
            }

            $this->handleFotoUpload($produk, $fotoBaruUntukUpload);
        }
    }
}
