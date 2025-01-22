<?php

declare(strict_types=1);

namespace App\Module\Routing\Events;

final readonly class CreateRoutingItemsCreatedEvent
{
    public function __construct(
        public int $routingId
    ) {
    }
}
