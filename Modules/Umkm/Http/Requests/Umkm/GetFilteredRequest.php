<?php

namespace Modules\Umkm\Http\Requests\Umkm;

use Illuminate\Foundation\Http\FormRequest;

class GetFilteredRequest extends FormRequest
{

    public function rules()
    {
        return [
            'instansi_id' => 'required|exists:instansi,id',
            'jenis' => 'nullable|exists:umkm_m_jenis,id',
            'bentuk' => 'nullable|exists:umkm_m_bentuk,id',
            'nama' => 'nullable|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'instansi_id.required' => 'Instansi wajib diisi.',
            'instansi_id.exists'   => 'Instansi tidak valid atau tidak ditemukan.',

            'jenis.exists'  => 'Jenis usaha tidak valid atau tidak ditemukan.',
            'bentuk.exists' => 'Bentuk usaha tidak valid atau tidak ditemukan.',

            'nama.string' => 'Nama UMKM harus berupa teks.',
            'nama.max'    => 'Nama UMKM tidak boleh lebih dari 100 karakter.',
        ];
    }
}
