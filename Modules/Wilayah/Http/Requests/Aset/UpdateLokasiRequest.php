<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLokasiRequest extends FormRequest
{

    public function rules()
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.numeric' => 'Latitude harus berupa angka.',
            'latitude.between' => 'Latitude harus berada di antara -90 dan 90.',

            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.numeric' => 'Longitude harus berupa angka.',
            'longitude.between' => 'Longitude harus berada di antara -180 dan 180.',
        ];
    }
}
