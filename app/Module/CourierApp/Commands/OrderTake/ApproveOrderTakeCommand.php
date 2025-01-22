<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

final class ApproveOrderTakeCommand
{
    public function __construct(
        public readonly int $id,
        public readonly int $places,
        public readonly int $userId,
    ) {
    }
}
