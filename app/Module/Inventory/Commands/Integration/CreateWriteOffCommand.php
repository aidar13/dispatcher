<?php

declare(strict_types=1);

namespace App\Module\Inventory\Commands\Integration;

use App\Module\Inventory\DTO\Integration\IntegrationWriteOffDTO;

final class CreateWriteOffCommand
{
    public function __construct(
        public readonly IntegrationWriteOffDTO $DTO
    ) {
    }
}
