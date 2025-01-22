<?php

declare(strict_types=1);

namespace App\Module\Inventory\DTO\Integration;

use Illuminate\Support\Collection;

final class IntegrationWriteOffItemCollectionDTO
{
    /**
     * @psalm-suppress InvalidArgument
     */
    public static function fromArray(array $writeOffItems): Collection
    {
        $writeOffItemCollection = collect();

        foreach ($writeOffItems as $writeOffItem) {
            $writeOffItemCollection->push(IntegrationWriteOffItemDTO::fromArray($writeOffItem)->toArray());
        }

        return $writeOffItemCollection;
    }
}
