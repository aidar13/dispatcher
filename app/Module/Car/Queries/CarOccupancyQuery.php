<?php

declare(strict_types=1);

namespace App\Module\Car\Queries;

use App\Module\Car\Contracts\Queries\CarOccupancyQuery as CarOccupancyQueryContract;
use App\Module\Car\Models\CarOccupancy;
use Carbon\CarbonImmutable;

final class CarOccupancyQuery implements CarOccupancyQueryContract
{
    public function getCurrent(int $carId, int $userId): ?CarOccupancy
    {
        return CarOccupancy::where('user_id', $userId)
            ->where('car_id', $carId)
            ->where('created_at', '>=', CarbonImmutable::now()->toDateString())
            ->orderByDesc('created_at')
            ->first();
    }

    public function getById(int $id): ?CarOccupancy
    {
        /** @var CarOccupancy|null */
        return CarOccupancy::query()
            ->where('id', $id)
            ->first();
    }
}
