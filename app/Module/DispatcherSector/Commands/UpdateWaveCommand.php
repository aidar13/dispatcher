<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\WaveDTO;

final class UpdateWaveCommand
{
    public function __construct(
        public readonly int $id,
        public readonly WaveDTO $DTO
    ) {
    }
}
