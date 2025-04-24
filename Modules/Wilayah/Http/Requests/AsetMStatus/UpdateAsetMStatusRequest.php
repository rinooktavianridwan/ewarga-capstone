<?php

namespace Modules\Wilayah\Http\Requests\AsetMStatus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetMStatusRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nama' => 'sometimes|required|string|max:100',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
