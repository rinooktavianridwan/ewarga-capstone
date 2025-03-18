<?php

namespace App\Services;

use App\Exceptions\FlowException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Closure;

class AuthorizationService
{
    public function validasi(User $user, $instansi_id, Closure $callback)
    {
        $cekUserAkses = $user->isOwner($instansi_id) || $user->isPengurus($instansi_id);

        if (!$cekUserAkses) {
            throw new AuthorizationException("Akses hanya untuk pengurus");
        }

        return $callback();
    }

    private function validasiUserByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    private function validasiGetToken(User $user, array $credentials)
    {
        if (!Hash::check($credentials['password'], $user->password)) {
            throw new FlowException("Email atau password salah");
        }
    }

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
