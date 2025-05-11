<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UmkmFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'instansi_id' => 'required|exists:instansi,id',
            'jenis_usaha_id' => 'nullable|exists:umkm_m_jenis,id',
            'bentuk_usaha_id' => 'nullable|exists:umkm_m_bentuk,id',
            'search' => 'nullable|string|max:100',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
