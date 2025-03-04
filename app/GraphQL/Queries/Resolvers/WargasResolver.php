<?php declare(strict_types=1);

namespace App\GraphQL\Queries\Resolvers;

use App\Models\Keluarga;
use App\Models\Warga;
use App\Models\WargaAnggotaKeluarga;
use App\Services\WargaService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Collection;

final class WargasResolver
{
    public function daftar(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        $user = auth()->user();
        return app(WargaService::class)->listWarga($args, $user);
    }

}
