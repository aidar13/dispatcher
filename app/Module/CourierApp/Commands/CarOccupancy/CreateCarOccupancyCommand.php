<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\CarOccupancy;

use App\Module\CourierApp\DTO\CarOccupancy\DeliveryCarOccupancyDTO;
use App\Module\CourierApp\DTO\CarOccupancy\OrderTakeCarOccupancyDTO;

class CreateCarOccupancyCommand
{
    public function __construct(
        public int $userId,
        public OrderTakeCarOccupancyDTO|DeliveryCarOccupancyDTO $DTO,
    ) {
    }
}
