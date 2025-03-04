<?php

namespace App\GraphQL\Types\Resolvers;

use App\Models\Instansi;
use App\Models\KasSaldo;
use App\Models\KasTransaksi;
use App\Services\KasTransaksiService;

class InstansiResolver
{

    public function isOwner(Instansi $instansi, array $args)
    {
        $user = auth()->user();
        return $user->isOwner($instansi->id);
    }
    public function isPengurus(Instansi $instansi, array $args)
    {
        $user = auth()->user();
        return $user->isPengurus($instansi->id);
    }

}
