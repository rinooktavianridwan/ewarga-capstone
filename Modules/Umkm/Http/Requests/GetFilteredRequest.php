<?php

namespace Modules\Umkm\Http\Requests;

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
}
