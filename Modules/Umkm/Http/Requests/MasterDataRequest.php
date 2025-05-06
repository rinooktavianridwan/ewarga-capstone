<?php

namespace Modules\Umkm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MasterDataRequest extends FormRequest
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
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama tidak boleh lebih dari 255 karakter.',
        ];
    }
}
