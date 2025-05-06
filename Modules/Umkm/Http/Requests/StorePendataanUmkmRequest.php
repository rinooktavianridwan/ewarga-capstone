<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendataanUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'instansi_id' => 'required|exists:instansi,id',
            'bentuk_usaha_id' => 'nullable|exists:umkm_M_bentuk,id',
            'jenis_usaha_id' => 'nullable|exists:umkm_M_jenis,id',
            'keterangan' => 'nullable|string',
            'warga_ids' => 'nullable|array',
            'warga_ids.*' => 'exists:warga,id',
            'kontak' => 'nullable|array',
            'kontak.*' => 'nullable|string',
            'alamat' => 'nullable|array',
            'alamat.*.latitude' => 'required_with:alamat|numeric',
            'alamat.*.longitude' => 'required_with:alamat|numeric',
            'foto' => 'nullable|array', // Foto menjadi opsional
            'foto.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Validasi untuk file foto
        ];
    }
}
