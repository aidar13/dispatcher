<?php

declare(strict_types=1);

namespace App\Module\Take\Events;

final class OrderTakesAssignedToCourierEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $courierId
    ) {
    }
}
