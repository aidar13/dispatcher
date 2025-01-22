<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery;

final class CalculateCarOccupancyCommand
{
    public function __construct(
        public readonly int $deliveryId
    ) {
    }
}
