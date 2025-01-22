<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\UpdateDispatcherSectorDTO;

final class UpdateDispatcherSectorCommand
{
    public function __construct(
        public readonly int $dispatchersSectorId,
        public readonly UpdateDispatcherSectorDTO $DTO
    ) {
    }
}
