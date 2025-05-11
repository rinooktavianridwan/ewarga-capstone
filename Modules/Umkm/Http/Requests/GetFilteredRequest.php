<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetFilteredRequest extends FormRequest
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
            'jenis' => 'nullable|exists:umkm_m_jenis,id',
            'bentuk' => 'nullable|exists:umkm_m_bentuk,id',
            'nama' => 'nullable|string|max:100',
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
