<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendataanUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'sometimes|required|string|max:255',
            'keterangan' => 'nullable|string',
            'instansi_id' => 'sometimes|required|exists:instansi,id',
            'bentuk_usaha_id' => 'sometimes|required|exists:umkm_M_bentuk,id',
            'jenis_usaha_id' => 'sometimes|required|exists:umkm_M_jenis,id',
            'warga_ids' => 'nullable|array',
            'warga_ids.*' => 'exists:warga,id',
            'kontak' => 'nullable|array',
            'kontak.*' => 'string|max:255',
            'alamat' => 'nullable|array',
            'alamat.*.latitude' => 'required_with:alamat|numeric',
            'alamat.*.longitude' => 'required_with:alamat|numeric',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
