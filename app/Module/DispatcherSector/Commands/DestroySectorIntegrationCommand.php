<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

final class DestroySectorIntegrationCommand
{
    public function __construct(
        public readonly int $sectorId
    ) {
    }
}
