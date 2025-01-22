<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\SendToAssemblyDTO;

final class SendContainersToAssemblyCommand
{
    public function __construct(
        public readonly SendToAssemblyDTO $DTO
    ) {
    }
}
