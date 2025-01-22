<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Queries;

use App\Module\Car\Models\CarOccupancy;

interface CarOccupancyQuery
{
    public function getCurrent(int $carId, int $userId): ?CarOccupancy;

    public function getById(int $id): ?CarOccupancy;
}
