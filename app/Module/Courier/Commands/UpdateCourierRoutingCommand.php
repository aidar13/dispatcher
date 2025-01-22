<?php

declare(strict_types=1);

namespace App\Module\Courier\Commands;

final readonly class UpdateCourierRoutingCommand
{
    public function __construct(
        public int $courierId,
        public bool $routingEnabled
    ) {
    }
}
