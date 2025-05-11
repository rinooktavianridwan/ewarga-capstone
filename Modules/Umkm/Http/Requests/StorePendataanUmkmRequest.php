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
            'umkm_m_bentuk_id' => 'nullable|exists:umkm_m_bentuk,id',
            'umkm_m_jenis_id' => 'nullable|exists:umkm_m_jenis,id',
            'keterangan' => 'nullable|string',
            'warga_ids' => 'nullable|array',
            'warga_ids.*' => 'exists:warga,id',
            'kontak' => 'nullable|array',
            'kontak.*.umkm_m_kontak_id' => 'required|exists:umkm_m_kontak,id',
            'kontak.*.kontak' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'lokasi' => 'nullable|array',
            'lokasi.*.latitude' => 'required_with:lokasi|numeric',
            'lokasi.*.longitude' => 'required_with:lokasi|numeric',
            'foto' => 'nullable|array',
            'foto.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
