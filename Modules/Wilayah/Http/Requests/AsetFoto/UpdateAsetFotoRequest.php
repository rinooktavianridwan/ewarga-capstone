<?php

namespace Modules\Wilayah\Http\Requests\AsetFoto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetFotoRequest extends FormRequest
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
            'file_path' => 'sometimes|required|string|max:255',
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
