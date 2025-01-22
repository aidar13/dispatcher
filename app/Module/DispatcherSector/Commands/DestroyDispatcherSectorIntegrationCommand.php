<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

final class DestroyDispatcherSectorIntegrationCommand
{
    public function __construct(
        public readonly int $dispatcherSectorId
    ) {
    }
}
