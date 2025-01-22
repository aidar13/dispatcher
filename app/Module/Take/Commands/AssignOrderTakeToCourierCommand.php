<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

final class AssignOrderTakeToCourierCommand
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $courierId,
        public readonly int $userId
    ) {
    }
}
