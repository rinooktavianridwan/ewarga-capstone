<?php

namespace Modules\Wilayah\Http\Requests\AsetMStatus;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetMStatusRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
