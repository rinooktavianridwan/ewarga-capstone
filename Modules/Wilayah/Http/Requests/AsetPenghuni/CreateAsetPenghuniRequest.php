<?php

namespace Modules\Wilayah\Http\Requests\AsetPenghuni;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetPenghuniRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'penghuni' => 'required|array|min:1',
            'penghuni.*.warga_id' => 'required|exists:warga,id,is_deleted,0',
            'penghuni.*.aset_m_status_id' => 'required|exists:aset_m_status,id,deleted_at,NULL',
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
