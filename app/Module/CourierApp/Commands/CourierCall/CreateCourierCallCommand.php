<?php

namespace App\Module\CourierApp\Commands\CourierCall;

use App\Module\CourierApp\DTO\CourierCall\CreateDeliveryCourierCallDTO;
use App\Module\CourierApp\DTO\CourierCall\CreateOrderTakeCourierCallDTO;

class CreateCourierCallCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly CreateOrderTakeCourierCallDTO|CreateDeliveryCourierCallDTO $DTO
    ) {
    }
}
