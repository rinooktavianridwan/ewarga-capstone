<?php

namespace Modules\Wilayah\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsetFotoRequest extends FormRequest
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
            'file_path' => 'required|string|max:255',
            'aset_id' => 'required|exists:aset,id',
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
