<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Events;

final class SectorDestroyedEvent
{
    public function __construct(
        public readonly int $sectorId
    ) {
    }
}
