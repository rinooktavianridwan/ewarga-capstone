<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendataanProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'umkm_id' => 'required|exists:umkm,id',
            'instansi_id' => 'required|exists:instansi,id',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'harga' => 'required|integer',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
