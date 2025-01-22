<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

final class UpdateSectorIntegrationCommand
{
    public function __construct(
        public readonly int $sectorId
    ) {
    }
}
