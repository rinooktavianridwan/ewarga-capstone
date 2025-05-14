<?php

namespace Modules\Umkm\Http\Requests\UmkmProduk;

use Illuminate\Foundation\Http\FormRequest;

class GetFilteredRequest extends FormRequest
{

    public function rules()
    {
        return [
            'instansi_id' => 'required|exists:instansi,id',
            'umkm_id' => 'required|exists:umkm,id',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'instansi_id.required' => 'Instansi ID wajib diisi.',
            'instansi_id.exists'   => 'Instansi ID tidak valid atau tidak ditemukan.',

            'umkm_id.required' => 'UMKM ID wajib diisi.',
            'umkm_id.exists'   => 'UMKM ID tidak valid atau tidak ditemukan.',
        ];
    }
}
