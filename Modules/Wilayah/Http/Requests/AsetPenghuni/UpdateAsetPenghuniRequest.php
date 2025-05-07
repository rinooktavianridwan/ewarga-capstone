<?php

namespace Modules\Wilayah\Http\Requests\AsetPenghuni;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetPenghuniRequest extends FormRequest
{

    public function rules()
    {
        return [
            'penghuni' => 'sometimes|required|array|min:1',
            'penghuni.*.warga_id' => 'sometimes|required|exists:warga,id,is_deleted,0',
            'penghuni.*.aset_m_status_id' => 'sometimes|required|exists:aset_m_status,id,deleted_at,NULL',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'penghuni.required' => 'Data penghuni wajib diisi.',
            'penghuni.array' => 'Data penghuni harus berupa array.',
            'penghuni.min' => 'Minimal harus ada satu penghuni yang diinput.',

            'penghuni.*.warga_id.required' => 'Warga ID pada setiap penghuni wajib diisi.',
            'penghuni.*.warga_id.exists' => 'Warga ID pada setiap penghuni tidak valid atau telah dihapus.',

            'penghuni.*.aset_m_status_id.required' => 'Status penghuni wajib diisi.',
            'penghuni.*.aset_m_status_id.exists' => 'Status penghuni tidak valid atau telah dihapus.',
        ];
    }
}
