<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterWargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instansi_id' => 'required|exists:instansi,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:warga,nik',
            'no_kk' => 'nullable|string|max:16',
            'no_tlp' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|in:L,P',
            'alamat' => 'nullable|string',
        ];
    }
}
