<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

final readonly class SectorCreatedEvent
{
    public function __construct(
        public int $sectorId
    ) {
    }
}
