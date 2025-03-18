<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AuthorizationService;
use Exception;

class AuthController extends Controller
{
    protected $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        try {
            $data = $this->authorizationService->createToken($request->only('email', 'password'));

            return response()->json([
                'user' => $data['user'],
                'access_token' => $data['access_token'],
                'token_type' => $data['token_type']
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }

        // Menghapus hanya token sesi saat ini (logout dari perangkat ini)
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil'], 200);
    }

    public function logoutAll(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak terautentikasi'], 401);
        }

        // Menghapus semua token user (logout dari semua perangkat)
        $user->tokens()->delete();

        return response()->json(['message' => 'Logout dari semua perangkat berhasil'], 200);
    }
}
