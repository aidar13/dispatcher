<?php

declare(strict_types=1);

namespace App\Module\Planning\Events;

use Illuminate\Support\Collection;

final class ContainersSentToAssemblyEvent
{
    public function __construct(
        public readonly array $containerIds,
        public readonly Collection $oneCContainers
    ) {
    }
}
