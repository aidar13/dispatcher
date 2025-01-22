<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

final class CreateCourierStopCommand
{
    public function __construct(
        public readonly int $clientId,
        public readonly string $clientType,
        public readonly int $courierId,
    ) {
    }
}
