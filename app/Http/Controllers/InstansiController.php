<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
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

        $instansi = Instansi::findOrFail($instansiId);
        return response()->json(['is_owner' => $user->isOwner($instansi->id)]);
    }

    // Mengecek apakah user adalah pengurus dari instansi tertentu
    public function isPengurus($instansiId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $instansi = Instansi::findOrFail($instansiId);
        return response()->json(['is_pengurus' => $user->isPengurus($instansi->id)]);
    }
}
