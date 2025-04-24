<?php

namespace App\Http\Controllers;

use App\Services\AuthorizationService;
use Illuminate\Http\Request;

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
            'password' => 'required|string',
        ]);

        try {
            $user = $this->authorizationService->createToken($request->only('email', 'password'));

            return response()->json([
                'access_token' => $user->token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete(); // Menghapus semua token autentikasi pengguna
        }

        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}
