<?php

namespace Modules\Umkm\Services;

use Modules\Umkm\Entities\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProdukService
{
    /**
     * Mengambil semua produk berdasarkan `umkm_id`.
     */
    public function getAllProdukByUmkmId($umkm_id)
    {
        $produk = Produk::where('umkm_id', $umkm_id)->get();

        if ($produk->isEmpty()) {
            return ['message' => 'UMKM ini tidak memiliki produk.'];
        }

        return $produk;
    }

    /**
     * Mengambil satu produk berdasarkan ID.
     */
    public function getProdukById($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            throw ValidationException::withMessages(['message' => 'Produk tidak ditemukan']);
        }
        return $produk;
    }

    /**
     * Membuat produk baru.
     */
    public function createProduk(array $data)
    {
        $validator = validator($data, [
            'nama' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'umkm_id' => 'required|exists:umkm,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
            $data['foto'] = $data['foto']->store('produk_foto', 'public');
        }

        return Produk::create($data);
    }

    /**
     * Memperbarui produk berdasarkan ID.
     */
    public function updateProduk($id, array $data)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            throw ValidationException::withMessages(['message' => 'Produk tidak ditemukan']);
        }

        if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $data['foto'] = $data['foto']->store('produk_foto', 'public');
        } else {
            unset($data['foto']);
        }

        $produk->update($data);

        return $produk;
    }

    /**
     * Menghapus produk berdasarkan ID.
     */
    public function deleteProduk($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            throw ValidationException::withMessages(['message' => 'Produk tidak ditemukan']);
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();
    }
}
