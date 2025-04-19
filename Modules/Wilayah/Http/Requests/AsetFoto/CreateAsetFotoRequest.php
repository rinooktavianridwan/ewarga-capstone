<?php

namespace Modules\Wilayah\Http\Requests\AsetFoto;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetFotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'aset_id' => 'required|exists:aset,id',
            'nama' => 'required|string|max:100',
            'file_path' => 'required|string|max:255',
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
