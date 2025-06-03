<?php

namespace Modules\Umkm\Http\Requests\Umkm;

use Illuminate\Foundation\Http\FormRequest;

class CreateUmkmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'instansi_id' => ['required', 'exists:instansi,id'],
            'umkm_m_bentuk_id' => ['required', 'exists:umkm_m_bentuk,id'],
            'umkm_m_jenis_id' => ['required', 'exists:umkm_m_jenis,id'],
            'keterangan' => ['nullable', 'string'],
            'warga_ids' => ['required', 'array'],
            'warga_ids.*' => ['required', 'exists:warga,id'],
            'kontak' => ['nullable', 'array'],
            'kontak.*.umkm_m_kontak_id' => ['sometimes', 'exists:umkm_m_kontak,id'],
            'kontak.*.kontak' => ['sometimes', 'string', 'max:100'],
            'alamat' => ['required', 'string'],
            'lokasi' => ['required', 'array'],
            'lokasi.*.latitude' => ['required_with:lokasi', 'numeric'],
            'lokasi.*.longitude' => ['required_with:lokasi', 'numeric'],
            'fotos' => ['required', 'array', 'max:5'],
            'fotos.*' => ['required', 'file', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama' => [
                'required' => 'Nama UMKM wajib diisi.',
                'string' => 'Nama UMKM harus berupa teks.',
                'max' => 'Nama UMKM tidak boleh lebih dari 255 karakter.',
            ],
            'instansi_id' => [
                'required' => 'Instansi wajib dipilih.',
                'exists' => 'Instansi tidak ditemukan.',
            ],
            'umkm_m_bentuk_id' => [
                'required' => 'Bentuk usaha wajib dipilih.',
                'exists' => 'Bentuk usaha tidak valid.',
            ],
            'umkm_m_jenis_id' => [
                'required' => 'Jenis usaha wajib dipilih.',
                'exists' => 'Jenis usaha tidak valid.',
            ],
            'keterangan' => [
                'string' => 'Keterangan harus berupa teks.',
            ],
            'warga_ids' => [
                'required' => 'Data warga wajib diisi.',
                'array' => 'Data warga harus berupa array.',
            ],
            'warga_ids.*' => [
                'required' => 'Setiap ID warga wajib diisi.',
                'exists' => 'Salah satu warga tidak ditemukan.',
            ],
            'kontak' => [
                'array' => 'Data kontak harus berupa array.',
            ],
            'kontak.*.umkm_m_kontak_id' => [
                'required' => 'Jenis kontak wajib diisi.',
                'exists' => 'Jenis kontak tidak ditemukan.',
            ],
            'kontak.*.kontak' => [
                'required' => 'Isi kontak wajib diisi.',
                'string' => 'Isi kontak harus berupa teks.',
                'max' => 'Isi kontak tidak boleh lebih dari 255 karakter.',
            ],
            'alamat' => [
                'required' => 'Alamat UMKM wajib diisi.',
                'string' => 'Alamat harus berupa teks.',
            ],
            'lokasi' => [
                'required' => 'Lokasi UMKM wajib diisi.',
                'array' => 'Lokasi harus berupa array.',
            ],
            'lokasi.*.latitude' => [
                'required_with' => 'Latitude lokasi wajib diisi.',
                'numeric' => 'Latitude harus berupa angka.',
            ],
            'lokasi.*.longitude' => [
                'required_with' => 'Longitude lokasi wajib diisi.',
                'numeric' => 'Longitude harus berupa angka.',
            ],
            'fotos' => [
                'required' => 'Foto umkm wajib diunggah.',
                'array' => 'Foto umkm harus berupa array.',
                'max' => 'Jumlah foto umkm tidak boleh lebih dari 5.',
            ],
            'fotos.*.required' => 'Setiap file foto wajib diisi.',
            'fotos.*.file' => 'Setiap file foto harus berupa file.',
            'fotos.*.image' => 'Setiap file foto umkm harus berupa gambar.',
            'fotos.*.max' => 'Ukuran setiap file foto tidak boleh lebih dari 2MB.',
        ];
    }
}
