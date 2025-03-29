<?php

namespace App\GraphQL\Types\Resolvers;

use App\Models\User;
use App\Models\Warga;
// class UserResolver
// {
//     public function warga(User $user, array $args)
//     {
//         return Warga::whereNull('instansi_id')->where('user_id', $user->id)->first();

//     }
//     public function isOwner(User $user, array $args)
//     {
//         $instansi_id = $args['instansi_id'];
//         return $user->isOwner($instansi_id);
//     }
//     public function isPengurus(User $user, array $args)
//     {
//         $instansi_id = $args['instansi_id'];
//         return $user->isPengurus($instansi_id);
//     }
// }
