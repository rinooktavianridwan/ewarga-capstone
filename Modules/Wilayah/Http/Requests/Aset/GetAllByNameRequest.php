<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class GetAllByNameRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
