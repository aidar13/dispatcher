<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use Illuminate\Support\Collection;

final class OnecContainerCollectionDTO
{
    public static function fromArray(array $items): Collection
    {
        $containers = [];

        foreach ($items as $container) {
            $containers[] = OnecContainerDTO::fromArray($container);
        }

        return collect($containers);
    }
}
