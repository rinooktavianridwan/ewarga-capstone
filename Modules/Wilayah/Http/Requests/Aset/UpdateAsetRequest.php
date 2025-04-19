<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama' => 'sometimes|required|string|max:100',
            'warga_id' => 'sometimes|required|exists:warga,id',
            'instansi_id' => 'sometimes|required|exists:instansi,id',
            'aset_m_jenis_id' => 'sometimes|required|exists:aset_m_jenis,id',
            'alamat' => 'sometimes|required|string',
            'lokasi' => 'sometimes|required',
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
