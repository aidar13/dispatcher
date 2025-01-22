<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\GenerateContainerDTO;

final class GenerateWaveContainersCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly GenerateContainerDTO $DTO
    ) {
    }
}
