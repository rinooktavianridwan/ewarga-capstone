<?php

namespace Modules\Umkm\Http\Requests\UmkmProduk;

use Illuminate\Foundation\Http\FormRequest;

class GetFilteredRequest extends FormRequest
{

    public function rules()
    {
        return [
            'instansi_id' => 'required|exists:instansi,id',
            'umkm_id' => 'required|exists:umkm,id',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
