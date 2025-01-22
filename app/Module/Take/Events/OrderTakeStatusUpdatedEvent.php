<?php

declare(strict_types=1);

namespace App\Module\Take\Events;

final readonly class OrderTakeStatusUpdatedEvent
{
    public function __construct(
        public int $orderTakeId
    ) {
    }
}
