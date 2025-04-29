<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nama' => 'sometimes|required|string|max:100',
            'warga_id' => 'sometimes|required|exists:warga,id,is_deleted,0',
            'instansi_id' => 'sometimes|required|exists:instansi,id,is_deleted,0',
            'aset_m_jenis_id' => 'sometimes|required|exists:aset_m_jenis,id,deleted_at,NULL',
            'alamat' => 'sometimes|required|string',
            'lokasi' => 'sometimes|required',
            'fotos' => 'sometimes|array',
            'fotos.*' => 'file|image|max:2048',
            'hapus_foto' => 'sometimes|array|max:5',
            'hapus_foto.*' => 'integer|exists:aset_foto,id,deleted_at,NULL',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
