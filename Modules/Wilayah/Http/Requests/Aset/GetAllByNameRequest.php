<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class GetAllByNameRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:100'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'string' => 'Nama harus berupa teks.',
                'max' => 'Nama tidak boleh lebih dari 100 karakter.',
            ],
        ];
    }
}
