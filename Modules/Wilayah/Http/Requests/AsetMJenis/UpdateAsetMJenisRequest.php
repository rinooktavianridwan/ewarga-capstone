<?php

namespace Modules\Wilayah\Http\Requests\AsetMJenis;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetMJenisRequest extends FormRequest
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
