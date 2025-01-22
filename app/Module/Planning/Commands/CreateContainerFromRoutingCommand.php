<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

final readonly class CreateContainerFromRoutingCommand
{
    public function __construct(
        public int $routingId,
    ) {
    }
}
