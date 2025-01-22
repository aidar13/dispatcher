<?php

declare(strict_types=1);

namespace App\Module\Gateway\DTO;

use App\Module\Gateway\Models\GatewayUser;
use Illuminate\Support\Collection;

final class GatewayUserCollectionDTO
{
    /**
     * @psalm-suppress InvalidArgument
     * @param Collection $gatewayUsers
     * @return Collection
     */
    public static function fromCollection(Collection $gatewayUsers): Collection
    {
        $gatewayUserCollection = collect();

        foreach ($gatewayUsers as $user) {
            $gatewayUserCollection->push(GatewayUser::fromArrayOfObjects($user));
        }

        return $gatewayUserCollection;
    }
}
