<?php

namespace Modules\Umkm\Http\Requests\Umkm;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['sometimes', 'required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'instansi_id' => ['sometimes', 'required', 'exists:instansi,id'],
            'umkm_m_bentuk_id' => ['sometimes', 'required', 'exists:umkm_M_bentuk,id'],
            'umkm_m_jenis_id' => ['sometimes', 'required', 'exists:umkm_M_jenis,id'],
            'warga_ids' => ['required', 'array', 'min:1'],
            'warga_ids.*' => ['exists:warga,id'],
            'kontak' => ['nullable', 'array'],
            'kontak.*.id' => ['sometimes', 'exists:umkm_kontak,id'],
            'kontak.*.umkm_m_kontak_id' => ['required', 'exists:umkm_m_kontak,id'],
            'kontak.*.kontak' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'lokasi' => ['nullable', 'array'],
            'lokasi.*.latitude' => ['required_with:lokasi', 'numeric'],
            'lokasi.*.longitude' => ['required_with:lokasi', 'numeric'],
            'foto' => ['nullable', 'array', 'max:5'],
            'foto.*' => ['image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama' => [
                'required' => 'Nama UMKM wajib diisi.',
                'string'   => 'Nama UMKM harus berupa teks.',
                'max'      => 'Nama UMKM tidak boleh lebih dari 255 karakter.',
            ],
            'keterangan' => [
                'string' => 'Keterangan harus berupa teks.',
            ],
            'instansi_id' => [
                'required' => 'Instansi wajib diisi.',
                'exists'   => 'Instansi tidak valid atau telah dihapus.',
            ],
            'umkm_m_bentuk_id' => [
                'required' => 'Bentuk usaha wajib diisi.',
                'exists'   => 'Bentuk usaha tidak valid atau telah dihapus.',
            ],
            'umkm_m_jenis_id' => [
                'required' => 'Jenis usaha wajib diisi.',
                'exists'   => 'Jenis usaha tidak valid atau telah dihapus.',
            ],
            'warga_ids' => [
                'required' => 'Minimal satu warga harus dipilih.',
                'array'    => 'Warga harus berupa array.',
                'min'      => 'Minimal satu warga harus dipilih.',
            ],
            'warga_ids.*' => [
                'exists' => 'ID warga tidak valid atau telah dihapus.',
            ],
            'kontak' => [
                'array' => 'Kontak harus berupa array.',
            ],
            'kontak.*.id' => [
                'exists' => 'ID kontak tidak valid atau telah dihapus.',
            ],
            'kontak.*.umkm_m_kontak_id' => [
                'required' => 'Jenis kontak wajib diisi.',
                'exists'   => 'Jenis kontak tidak valid atau telah dihapus.',
            ],
            'kontak.*.kontak' => [
                'required' => 'Nilai kontak wajib diisi.',
                'string'   => 'Nilai kontak harus berupa teks.',
                'max'      => 'Nilai kontak tidak boleh lebih dari 255 karakter.',
            ],
            'alamat' => [
                'string' => 'Alamat harus berupa teks.',
            ],
            'lokasi' => [
                'array' => 'Lokasi harus berupa array.',
            ],
            'lokasi.*.latitude' => [
                'required_with' => 'Latitude wajib diisi jika lokasi diisi.',
                'numeric'        => 'Latitude harus berupa angka.',
            ],
            'lokasi.*.longitude' => [
                'required_with' => 'Longitude wajib diisi jika lokasi diisi.',
                'numeric'        => 'Longitude harus berupa angka.',
            ],
            'foto' => [
                'array' => 'Foto harus berupa array.',
                'max'   => 'Jumlah foto tidak boleh lebih dari 5.',
            ],
            'foto.*image' => 'Setiap file foto harus berupa gambar.',
            'foto.*max'   => 'Ukuran setiap file foto tidak boleh lebih dari 2MB.',
        ];
    }
}
