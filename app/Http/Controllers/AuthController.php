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

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}
