<?php

namespace Modules\Wilayah\Http\Requests\AsetPenghuni;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetPenghuniRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'warga_id' => 'sometimes|required|exists:warga,id',
            'aset_m_status_id' => 'sometimes|required|exists:aset_m_status,id',
            'aset_id' => 'sometimes|required|exists:aset,id',
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
