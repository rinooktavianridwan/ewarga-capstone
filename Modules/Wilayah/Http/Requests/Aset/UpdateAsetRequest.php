<?php

namespace Modules\Wilayah\Http\Requests\Aset;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsetRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => ['sometimes', 'required', 'string', 'max:100'],
            'warga_id' => ['sometimes', 'required', 'exists:warga,id,is_deleted,0'],
            'instansi_id' => ['sometimes', 'required', 'exists:instansi,id,is_deleted,0'],
            'aset_m_jenis_id' => ['sometimes', 'required', 'exists:aset_m_jenis,id,deleted_at,NULL'],
            'alamat' => ['sometimes', 'required', 'string'],
            'lokasi' => ['sometimes', 'required'],
            'fotos' => ['sometimes', 'array'],
            'fotos.*' => ['file', 'image', 'max:2048'],
            'hapus_foto' => ['sometimes', 'array', 'max:5'],
            'hapus_foto.*' => ['integer', 'exists:aset_foto,id,deleted_at,NULL'],
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
                'required' => 'Nama aset wajib diisi.',
                'string' => 'Nama aset harus berupa teks.',
                'max' => 'Nama aset tidak boleh lebih dari 100 karakter.',
            ],
            'warga_id' => [
                'required' => 'Warga ID wajib diisi.',
                'exists' => 'Warga ID tidak valid atau telah dihapus.',
            ],
            'instansi_id' => [
                'required' => 'Instansi ID wajib diisi.',
                'exists' => 'Instansi ID tidak valid atau telah dihapus.',
            ],
            'aset_m_jenis_id' => [
                'required' => 'Jenis aset wajib diisi.',
                'exists' => 'Jenis aset tidak valid atau telah dihapus.',
            ],
            'alamat' => [
                'required' => 'Alamat aset wajib diisi.',
                'string' => 'Alamat aset harus berupa teks.',
            ],
            'lokasi' => [
                'required' => 'Lokasi aset wajib diisi.',
            ],
            'fotos' => [
                'sometimes' => 'Foto aset harus berupa array jika diunggah.',
                'array' => 'Foto aset harus berupa array.',
                '*.file' => 'Setiap file foto aset harus berupa file yang valid.',
                '*.image' => 'Setiap file foto aset harus berupa gambar.',
                '*.max' => 'Ukuran setiap file foto aset tidak boleh lebih dari 2MB.',
            ],
            'hapus_foto' => [
                'array' => 'Hapus foto harus berupa array.',
                'max' => 'Jumlah foto yang dihapus tidak boleh lebih dari 5.',
                '*.integer' => 'Setiap ID foto yang dihapus harus berupa angka.',
                '*.exists' => 'ID foto yang dihapus tidak valid atau telah dihapus.',
            ],
        ];
    }
}
