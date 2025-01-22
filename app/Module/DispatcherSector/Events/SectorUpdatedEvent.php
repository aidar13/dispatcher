<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

final class SectorUpdatedEvent
{
    public function __construct(
        public readonly int $sectorId
    ) {
    }
}
