<?php

namespace Modules\Wilayah\Http\Requests\AsetMaster;

use Illuminate\Foundation\Http\FormRequest;

class AsetMasterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'nama' => [
                'required' => 'Nama wajib diisi.',
                'string' => 'Nama harus berupa teks.',
                'max' => 'Nama tidak boleh lebih dari 100 karakter.',
            ],
        ];
    }
}
