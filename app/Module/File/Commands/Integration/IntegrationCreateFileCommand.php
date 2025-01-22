<?php

declare(strict_types=1);

namespace App\Module\File\Commands\Integration;

use App\Module\File\DTO\Integration\IntegrationFileDTO;

final class IntegrationCreateFileCommand
{
    public function __construct(
        public readonly IntegrationFileDTO $DTO
    ) {
    }
}
