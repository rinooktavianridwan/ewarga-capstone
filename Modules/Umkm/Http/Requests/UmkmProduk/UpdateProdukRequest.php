<?php

namespace Modules\Umkm\Http\Requests\UmkmProduk;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'umkm_id'     => ['required', 'exists:umkm,id'],
            'instansi_id' => ['required', 'exists:instansi,id'],
            'nama'        => ['sometimes', 'required', 'string', 'max:255'],
            'keterangan'  => ['nullable', 'string'],
            'harga'       => ['sometimes', 'required', 'integer'],
            'fotos'        => ['nullable', 'array', 'max:5'],
            'fotos.*'      => ['image', 'max:2048'],
            'hapus_foto' => ['sometimes', 'array', 'max:5'],
            'hapus_foto.*' => ['integer', 'exists:umkm_foto,id,deleted_at,NULL'],
        ];
    }

    public function messages(): array
    {
        return [
            'umkm_id' => [
                'required' => 'UMKM ID wajib diisi.',
                'exists'   => 'UMKM ID tidak valid atau tidak ditemukan.',
            ],
            'instansi_id' => [
                'required' => 'Instansi ID wajib diisi.',
                'exists'   => 'Instansi ID tidak valid atau tidak ditemukan.',
            ],
            'nama' => [
                'required' => 'Nama produk wajib diisi.',
                'string'   => 'Nama produk harus berupa teks.',
                'max'      => 'Nama produk tidak boleh lebih dari 255 karakter.',
            ],
            'keterangan' => [
                'string' => 'Keterangan produk harus berupa teks.',
            ],
            'harga' => [
                'required' => 'Harga produk wajib diisi.',
                'integer'  => 'Harga produk harus berupa angka.',
            ],
            'fotos' => [
                'array' => 'Foto produk harus berupa array.',
                'max'   => 'Jumlah foto tidak boleh lebih dari 5.',
            ],
            'fotos.*.image' => 'Setiap file foto harus berupa gambar.',
            'fotos.*.max'   => 'Ukuran setiap file foto tidak boleh lebih dari 2MB.',
        ];
    }
}
