<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class CreateAsetRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'warga_id' => 'required|exists:warga,id,is_deleted,0',
            'instansi_id' => 'required|exists:instansi,id,is_deleted,0',
            'aset_m_jenis_id' => 'required|exists:aset_m_jenis,id,deleted_at,NULL',
            'alamat' => 'required|string',
            'lokasi' => 'nullable',
            'fotos' => 'sometimes|array|max:5',
            'fotos.*' => 'file|image|max:2048',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama aset wajib diisi.',
            'nama.string' => 'Nama aset harus berupa teks.',
            'nama.max' => 'Nama aset tidak boleh lebih dari 100 karakter.',

            'warga_id.required' => 'Warga ID wajib diisi.',
            'warga_id.exists' => 'Warga ID tidak valid atau telah dihapus.',

            'instansi_id.required' => 'Instansi ID wajib diisi.',
            'instansi_id.exists' => 'Instansi ID tidak valid atau telah dihapus.',

            'aset_m_jenis_id.required' => 'Jenis aset wajib diisi.',
            'aset_m_jenis_id.exists' => 'Jenis aset tidak valid atau telah dihapus.',

            'alamat.required' => 'Alamat aset wajib diisi.',
            'alamat.string' => 'Alamat aset harus berupa teks.',

            'fotos.sometimes' => 'Foto aset harus berupa array jika diunggah.',
            'fotos.array' => 'Foto aset harus berupa array.',
            'fotos.max' => 'Jumlah foto aset tidak boleh lebih dari 5.',
            'fotos.*.file' => 'Setiap file foto aset harus berupa file yang valid.',
            'fotos.*.image' => 'Setiap file foto aset harus berupa gambar.',
            'fotos.*.max' => 'Ukuran setiap file foto aset tidak boleh lebih dari 2MB.',
        ];
    }
}
