<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use App\Module\DispatcherSector\DTO\WaveDTO;

final class CreateWaveCommand
{
    public function __construct(public readonly WaveDTO $DTO)
    {
    }
}
