<?php

namespace Modules\Umkm\Http\Requests\UmkmProduk;

use Illuminate\Foundation\Http\FormRequest;

class CreateProdukRequest extends FormRequest
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
            'nama'        => ['required', 'string', 'max:255'],
            'keterangan'  => ['nullable', 'string'],
            'harga'       => ['required', 'integer'],
            'fotos'        => ['required', 'array', 'max:5'],
            'fotos.*'      => ['required', 'file', 'image', 'max:2048'],
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
                'required' => 'Foto produk wajib diisi.',
                'array' => 'Foto produk produk harus berupa array.',
                'max'   => 'Jumlah foto produk tidak boleh lebih dari 5.',
            ],
            'fotos.*.required' => 'Setiap file foto wajib diisi.',
            'fotos.*.file' => 'Setiap file foto harus berupa file.',
            'fotos.*.image' => 'Setiap file foto produk harus berupa gambar.',
            'fotos.*.max'   => 'Ukuran setiap file foto produk tidak boleh lebih dari 2MB.',
        ];
    }
}
