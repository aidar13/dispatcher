<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

final class GenerateSectorContainersCommand
{
    public function __construct(
        public readonly int $sectorId,
        public readonly int $waveId,
        public readonly string $date,
        public readonly int $userId,
        public readonly ?int $statusId
    ) {
    }
}
