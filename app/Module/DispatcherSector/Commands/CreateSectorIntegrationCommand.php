<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

final readonly class CreateSectorIntegrationCommand
{
    public function __construct(
        public int $sectorId
    ) {
    }
}
