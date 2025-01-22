<?php

declare(strict_types=1);

namespace App\Module\Routing\Events;

final readonly class RoutingCreatedEvent
{
    public function __construct(
        public int $routingId
    ) {
    }
}
