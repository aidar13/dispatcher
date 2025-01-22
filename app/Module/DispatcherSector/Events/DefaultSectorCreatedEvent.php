<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

final class DefaultSectorCreatedEvent
{
    public function __construct(
        public readonly int $sectorId
    ) {
    }
}
