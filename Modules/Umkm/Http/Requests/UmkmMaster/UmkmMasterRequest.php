<?php

namespace Modules\Umkm\Http\Requests\UmkmMaster;

use Illuminate\Foundation\Http\FormRequest;

class UmkmMasterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'nama' => [
                'required' => 'Nama wajib diisi.',
                'string' => 'Nama harus berupa teks.',
                'max' => 'Nama tidak boleh lebih dari 255 karakter.',
            ],
        ];
    }
}
