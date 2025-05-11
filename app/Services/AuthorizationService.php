<?php

namespace App\Services;

use App\Exceptions\FlowException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthorizationService
{
    public function createToken(array $credentials = []): array
    {
        if (!Auth::attempt($credentials)) {
            throw new FlowException("Email atau password salah");
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function deleteToken(User $user)
    {
        if ($user->tokens()->exists()) {
            $user->tokens()->delete();
        }
    }

    public function deleteSession()
    {
        $user = Auth::user();

        if (!$user) {
            throw new FlowException("User tidak terautentikasi");
        }

        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
    }
}
