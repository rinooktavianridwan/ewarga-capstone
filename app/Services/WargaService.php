<?php

namespace App\Services;

use App\Exceptions\FlowException;
use App\Models\User;
use App\Models\Warga;
use App\Models\WargaAjuan;
use App\Models\WargaInstansi;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class WargaService
{
    public function listWarga($params = [], $user){

        $keyword = $params['keyword'] ?? null;
        $instansi_id = $params['instansi_id'] ?? null;
        $wargas = $this->queryWarga()->where('instansi_id', $instansi_id);
        if($keyword){
            $wargas->where('nama', 'like', "%".$keyword."%");
        }
        return $wargas;

    }

    public function validasiWarga(int $wargaId, $instansi_id): Warga
    {
        if($instansi_id){
            $warga = Warga::where('instansi_id', $instansi_id)->where('id', $wargaId)->first();
        }else{
            $warga = Warga::whereNull('instansi_id')->where('id', $wargaId)->first();
        }


        if (!$warga) {
            throw new FlowException("Data warga tidak ditemukan");
        }

        return $warga;
    }

    public function validasiWargaByEmail(string $email, $instansi_id): Warga
    {

        if($instansi_id){
            $warga = Warga::where('instansi_id', $instansi_id)->where('email', $email)->first();
        }else{
            $warga = Warga::whereNull('instansi_id')->where('email', $email)->first();
        }
        if (!$warga) {
            throw new FlowException("Data warga tidak ditemukan");
        }

        return $warga;
    }

    public function validasiWargaByMe(int $userId)
    {

        $warga = Warga::whereNull('instansi_id')->where('user_id', $userId)
            ->where('created_by', $userId)
            ->first();

        if (!$warga) {
            throw new FlowException("Data warga tidak ditemukan");
        }

        return $warga;
    }

    public function queryWarga()
    {
        return Warga::query();
    }

    public function save(Warga $warga, $dataWarga)
    {
        $warga->fill($dataWarga);
        $warga->save();

        return $warga;
    }

}

