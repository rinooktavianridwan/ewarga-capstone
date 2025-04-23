<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetRequest extends FormRequest
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
            'warga_id' => 'required|exists:warga,id,deleted_at,NULL',
            'instansi_id' => 'required|exists:instansi,id,deleted_at,NULL',
            'aset_m_jenis_id' => 'required|exists:aset_m_jenis,id,deleted_at,NULL',
            'alamat' => 'required|string',
            'lokasi' => 'nullable',
            'fotos' => 'sometimes|array',
            'fotos.*' => 'file|image|max:2048',
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
