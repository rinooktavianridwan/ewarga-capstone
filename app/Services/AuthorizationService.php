<?php

namespace App\Services;

use App\Exceptions\FlowException;
use App\Jobs\JagrMnRemoveDeviceJob;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthorizationService
{
    public function validasi($user, $instansi_id, Closure $callback)
    {
        $cekUserAkses = ($user->isOwner($instansi_id) || $user->isPengurus($instansi_id));

        if (!$cekUserAkses) {
            throw new AuthorizationException("Akses hanya untuk pengurus");
        }

        return $callback();
    }

    public function validasiGetToken(User $user, $credential)
    {
        $password = $user->password ?? null;
        if (!Hash::check($credential['password'], $password)) {
            throw new FlowException("email atau password salah");
        }

        return true;
    }

    private function validasiUserByEmail(string $email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new FlowException("email atau password salah");
        }

        return $user;
    }

    public function createToken(array $credential = [])
    {
        $user = $this->validasiUserByEmail($credential['email']);
        $this->validasiGetToken($user, $credential);

        $user->fill($credential);
        $token       = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return $user;
    }

    public function deleteToken(User $user)
    {
        $user->tokens()->delete();

        return $user;
    }

    public function createSession($credential)
    {
        $guard = Auth::guard('web');

        if (!$guard->attempt($credential)) {
            throw new FlowException("Invalid credentials");
        }

        $user = $guard->user();
        assert($user instanceof User, 'Since we successfully logged in, this can no longer be `null`.');

        return $user;
    }

    public function deleteSession()
    {
        $guard = Auth::guard('web');

        $user = $guard->user();
        $user?->fcmRegToken()->delete();
        $guard->logout();

        return $user;
    }

}
