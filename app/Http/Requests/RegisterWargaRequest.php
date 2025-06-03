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
            'no_kk' => 'required|string|max:16',
            'no_tlp' => 'required|string|max:20',
            'tempat_lahir' => 'required|string|max:100',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
        ];
    }
}
