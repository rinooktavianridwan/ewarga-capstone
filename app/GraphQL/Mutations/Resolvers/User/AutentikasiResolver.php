<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Resolvers\User;

use App\Services\AuthorizationService;

final class AutentikasiResolver
{
    public function login($_, array $args)
    {
        $email      = $args['email'] ?? null;
        $password   = $args['password'] ?? null;
        $credential = [
          'email' => $email,
          'password' => $password
        ];

        $user = app(AuthorizationService::class)->createToken($credential);

        return $user;
    }

    public function logout($_, array $args)
    {
        return app(AuthorizationService::class)->deleteToken(auth()->user());
    }

}
