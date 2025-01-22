<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\CourierState;

use App\Module\CourierApp\DTO\CourierState\CreateDeliveryCourierStateDTO;
use App\Module\CourierApp\DTO\CourierState\CreateOrderTakeCourierStateDTO;

final class CreateCourierStateCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly CreateOrderTakeCourierStateDTO|CreateDeliveryCourierStateDTO $DTO,
    ) {
    }
}
