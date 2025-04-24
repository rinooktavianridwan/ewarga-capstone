<?php

namespace Modules\Wilayah\Http\Requests\AsetMJenis;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetMJenisRequest extends FormRequest
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
