<?php

namespace Modules\Umkm\Http\Requests;

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
            'nama'        => ['sometimes', 'required', 'string', 'max:255'],
            'keterangan'  => ['nullable', 'string'],
            'harga'       => ['sometimes', 'required', 'integer'],
            'foto'        => ['nullable', 'array', 'max:5'],
            'foto.*'      => ['image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
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
            'foto' => [
                'array' => 'Foto produk harus berupa array.',
                'max'   => 'Jumlah foto tidak boleh lebih dari 5.',
            ],
            'foto.*image' => 'Setiap file foto harus berupa gambar.',
            'foto.*max'   => 'Ukuran setiap file foto tidak boleh lebih dari 2MB.',
        ];
    }
}
