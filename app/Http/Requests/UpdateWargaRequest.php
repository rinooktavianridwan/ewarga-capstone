<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instansi_id' => 'sometimes|exists:instansi,id',
            'nama' => 'sometimes|string|max:255',
            'nik' => [
                'sometimes',
                'string',
                'max:16',
                Rule::unique('warga', 'nik')->ignore($this->route('id')),
            ],
            'no_kk' => 'sometimes|nullable|string|max:16',
            'no_tlp' => 'sometimes|nullable|string|max:20',
            'tempat_lahir' => 'sometimes|nullable|string|max:100',
            'tgl_lahir' => 'sometimes|nullable|date',
            'jenis_kelamin' => 'sometimes|nullable|in:L,P',
            'alamat' => 'sometimes|nullable|string',
            'foto' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
