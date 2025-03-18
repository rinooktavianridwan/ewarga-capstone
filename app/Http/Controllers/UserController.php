<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mengambil data Warga berdasarkan user_id
    public function getWarga($userId)
    {
        $warga = Warga::whereNull('instansi_id')->where('user_id', $userId)->first();

        if (!$warga) {
            return response()->json(['message' => 'Warga not found'], 404);
        }

        return response()->json($warga);
    }

    // Mengecek apakah user adalah owner dari instansi tertentu
    public function isOwner(Request $request, $userId)
    {
        $instansiId = $request->input('instansi_id');

        if (!$instansiId) {
            return response()->json(['message' => 'instansi_id is required'], 400);
        }

        $user = User::findOrFail($userId);
        return response()->json(['is_owner' => $user->isOwner($instansiId)]);
    }

    // Mengecek apakah user adalah pengurus dari instansi tertentu
    public function isPengurus(Request $request, $userId)
    {
        $instansiId = $request->input('instansi_id');

        if (!$instansiId) {
            return response()->json(['message' => 'instansi_id is required'], 400);
        }

        $user = User::findOrFail($userId);
        return response()->json(['is_pengurus' => $user->isPengurus($instansiId)]);
    }
}
