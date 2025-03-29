<?php

namespace Modules\Umkm\Services;

use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmProdukFoto;

class ProdukService
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

        // 🔍 Query produk
        $query = UmkmProduk::with(['umkm', 'instansi', 'fotos'])
            ->where('umkm_id', $umkmId)
            ->where('instansi_id', $instansiId);

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return $query->get();
    }


    public function getById($id)
    {
        return UmkmProduk::with(['umkm', 'instansi', 'fotos'])->findOrFail($id);
    }

    public function store(array $data, array $fotoFiles): UmkmProduk
    {

        $produk = UmkmProduk::create($data);
        $this->handleFotoUpload($produk, $fotoFiles);
        return $produk->load('fotos');
    }

    public function update($id, array $data, array $fotoFiles): UmkmProduk
    {
        $produk = UmkmProduk::findOrFail($id);
        $produk->update($data);

        if (!empty($fotoFiles)) {
            $fotoBaruHashes = [];
            $fileMap = [];

            foreach ($fotoFiles as $file) {
                $hash = md5_file($file->getRealPath());
                $fotoBaruHashes[] = $hash;
                $fileMap[$hash] = $file;
            }

            $fotoAktif = $produk->fotos()->get();
            $fotoSemua = $produk->fotos()->withTrashed()->get(); // semua (termasuk yang pernah dihapus)

            $hashFotoLama = [];
            foreach ($fotoSemua as $foto) {
                $path = storage_path("app/public/produk_foto/{$foto->nama}");
                if (file_exists($path)) {
                    $hashFotoLama[md5_file($path)] = $foto;
                }
            }

            $hashBaruYangSudahAda = [];
            $fotoBaruUntukUpload = [];

            foreach ($fotoBaruHashes as $hash) {
                if (isset($hashFotoLama[$hash])) {
                    $foto = $hashFotoLama[$hash];
                    if ($foto->trashed()) {
                        $foto->restore();
                    }
                    $hashBaruYangSudahAda[] = $hash;
                } else {
                    $fotoBaruUntukUpload[] = $fileMap[$hash];
                }
            }

            foreach ($fotoAktif as $foto) {
                $path = storage_path("app/public/produk_foto/{$foto->nama}");
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

        return $produk->load('fotos');
    }


    public function delete($id): void
    {
        $produk = UmkmProduk::findOrFail($id);

        foreach ($produk->fotos as $foto) {
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

            // Simpan file ke storage/app/public/produk_foto
            $file->storeAs('public', $path);

            // Simpan nama lengkap path ke database
            UmkmProdukFoto::create([
                'umkm_produk_id' => $produk->id,
                'nama' => $path, // ✅ include folder-nya
            ]);
        }
    }

}
