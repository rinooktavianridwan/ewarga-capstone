<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'warga_id' => 'required|exists:warga,id,is_deleted,0',
            'instansi_id' => 'required|exists:instansi,id,is_deleted,0',
            'aset_m_jenis_id' => 'required|exists:aset_m_jenis,id,deleted_at,NULL',
            'alamat' => 'required|string',
            'lokasi' => 'nullable',
            'fotos' => 'sometimes|array|max:5',
            'fotos.*' => 'file|image|max:2048',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
