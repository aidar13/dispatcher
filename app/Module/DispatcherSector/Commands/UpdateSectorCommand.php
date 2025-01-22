<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\UpdateSectorDTO;

final class UpdateSectorCommand
{
    public function __construct(
        public readonly int $id,
        public readonly UpdateSectorDTO $DTO
    ) {
    }
}
