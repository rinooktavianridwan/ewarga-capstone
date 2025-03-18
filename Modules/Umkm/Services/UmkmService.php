<?php

namespace Modules\Umkm\Services;

use Modules\Umkm\Entities\Umkm;
use App\Models\Warga;
use App\Models\Instansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UmkmService
{
    /**
     * Mendapatkan daftar UMKM berdasarkan warga yang sedang login.
     */

     public function getAllWargaByParentId()
     {
         $user = Auth::user();

         if (!$user) {
             abort(401, 'User tidak terautentikasi');
         }


         $wargaList = Warga::where('user_id', $user->id)->get();

         if ($wargaList->isEmpty()) {
             throw ValidationException::withMessages(['message' => 'Anda bukan warga']);
         }

         $instansiIds = $wargaList->pluck('instansi_id')->unique()->toArray();

         if (empty($instansiIds)) {
             throw ValidationException::withMessages(['message' => 'Tidak ada instansi yang terkait dengan warga Anda']);
         }

         $checkedInstansi = $instansiIds;
         do {
             $newInstansiIds = Instansi::whereIn('parent_id', $checkedInstansi)
                 ->whereNotIn('id', $checkedInstansi)
                 ->pluck('id')
                 ->toArray();

             if (!empty($newInstansiIds)) {
                 $checkedInstansi = array_merge($checkedInstansi, $newInstansiIds);
             }
         } while (!empty($newInstansiIds));

         $newWargaIds = Warga::whereIn('instansi_id', $checkedInstansi)
             ->pluck('id')
             ->toArray();

         if (empty($newWargaIds)) {
             throw ValidationException::withMessages(['message' => 'Tidak ada warga yang ditemukan dari instansi ini']);
         }

         return $newWargaIds;
     }

     public function getAllUmkm()
     {
         $newWargaIds = $this->getAllWargaByParentId();

         $tampilkanumkm = Umkm::whereIn('warga_id', $newWargaIds)->get();

         if ($tampilkanumkm->isEmpty()) {
             throw ValidationException::withMessages(['message' => 'Tidak ada UMKM yang ditemukan']);
         }

         return response()->json([
             'success' => true,
             'message' => 'Daftar UMKM berhasil diambil',
             'data' => $tampilkanumkm,
             'warga_id' => $newWargaIds
         ], 200);
     }

    /**
     * Menampilkan detail UMKM berdasarkan ID.
     */
    public function getUmkmById($id)
    {
        $umkm = Umkm::find($id);

        if (!$umkm) {
            throw ValidationException::withMessages(['message' => 'UMKM tidak ditemukan']);
        }

        return $umkm;
    }

        public function createUmkm(array $data)
    {
        $user = Auth::user();

        if (!$user) {
            throw ValidationException::withMessages(['message' => 'User tidak terautentikasi']);
        }

        $wargaDipilih = Warga::find($data['warga_id']);

        if (!$wargaDipilih) {
            throw ValidationException::withMessages(['message' => 'Warga tidak ditemukan']);
        }

        $fotoPath = null;
        if (isset($data['foto']) && $data['foto']->isValid()) {
            $fotoPath = $data['foto']->store('umkm_foto', 'public');
        }

        return Umkm::create([
            'nama' => $data['nama'],
            'jenis_usaha' => $data['jenis_usaha'],
            'kontak' => $data['kontak'],
            'warga_id' => $data['warga_id'],
            'alamat' => $data['alamat'],
            'foto' => $fotoPath,
        ]);
    }




    /**
     * Update UMKM berdasarkan ID.
     */
    public function updateUmkm($id, array $data)
    {
        $umkm = Umkm::find($id);

        if (!$umkm) {
            throw ValidationException::withMessages(['message' => 'UMKM tidak ditemukan']);
        }

        // Validasi input
        $validator = Validator::make($data, [
            'nama' => 'sometimes|required|string|max:255',
            'kontak' => 'sometimes|required|string|max:20',
            'alamat' => 'nullable|string',
            'jenis_usaha' => 'sometimes|required|in:online,offline',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        if (isset($data['foto']) && $data['foto']->isValid()) {

            if ($umkm->foto) {
                Storage::disk('public')->delete($umkm->foto);
            }

            $data['foto'] = $data['foto']->store('umkm_foto', 'public');
        }


        $umkm->update([
            'nama' => $data['nama'] ?? $umkm->nama,
            'kontak' => $data['kontak'] ?? $umkm->kontak,
            'alamat' => $data['alamat'] ?? $umkm->alamat,
            'jenis_usaha' => $data['jenis_usaha'] ?? $umkm->jenis_usaha,
            'foto' => $data['foto'] ?? $umkm->foto,
        ]);

        return $umkm;
    }

    /**
     * Menghapus UMKM berdasarkan ID.
     */
    public function deleteUmkm($id)
    {
        $umkm = Umkm::find($id);

        if (!$umkm) {
            throw ValidationException::withMessages(['message' => 'UMKM tidak ditemukan']);
        }

        if ($umkm->foto) {
            Storage::disk('public')->delete($umkm->foto);
        }

        $umkm->delete();
    }
}
