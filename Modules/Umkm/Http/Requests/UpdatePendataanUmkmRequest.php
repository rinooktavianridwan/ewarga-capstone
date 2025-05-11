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
            'umkm_m_bentuk_id' => 'sometimes|required|exists:umkm_M_bentuk,id',
            'umkm_m_jenis_id' => 'sometimes|required|exists:umkm_M_jenis,id',
            'warga_ids' => 'required|array|min:1',
            'warga_ids.*' => 'exists:warga,id',
            'kontak' => 'nullable|array',
            'kontak.*.id' => 'sometimes|exists:umkm_kontak,id',
            'kontak.*.umkm_m_kontak_id' => 'required|exists:umkm_m_kontak,id',
            'kontak.*.kontak' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'lokasi' => 'nullable|array',
            'lokasi.*.latitude' => 'required_with:lokasi|numeric',
            'lokasi.*.longitude' => 'required_with:lokasi|numeric',
            'foto' => 'nullable|array|max:5',
            'foto.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
