<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Warga;
use Illuminate\Http\Request;

class InstansiController extends Controller
{
    // Mengecek apakah user adalah owner dari instansi tertentu
    public function isOwner($instansiId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $warga = Warga::where('user_id', $user->id)->first();

        if (!$warga) {
            return response()->json(['message' => 'Warga tidak ditemukan'], 404);
        }

        return response()->json(['is_owner' => $warga->isOwner($instansiId)]);
    }

    // Mengecek apakah user adalah pengurus dari instansi tertentu
    public function isPengurus($instansiId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $warga = Warga::where('user_id', $user->id)->first();

        if (!$warga) {
            return response()->json(['message' => 'Warga tidak ditemukan'], 404);
        }

        return response()->json(['is_pengurus' => $warga->isPengurus($instansiId)]);
    }
}
