<?php

namespace Modules\Wilayah\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'warga_id' => 'required|exists:warga,id',
            'instansi_id' => 'required|exists:instansi,id',
            'aset_m_jenis_id' => 'required|exists:aset_m_jenis,id',
            'alamat' => 'required|string',
            'lokasi' => 'required',
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
