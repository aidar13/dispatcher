<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories\Integration;

interface CreateDeliveriesInCabinetRepository
{
    public function createDeliveries(string $routeSheetNumber, int $courierId): void;
}
