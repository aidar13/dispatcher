<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Queries\CourierLocation;

use App\Module\CourierApp\Contracts\Queries\CourierLocation\CourierLocationQuery as CourierLocationQueryContract;
use App\Module\CourierApp\Models\CourierLoaction;
use Carbon\Carbon;

final class CourierLocationQuery implements CourierLocationQueryContract
{
    public function getFirstNearbyLocationByCourierId(int $courierId, Carbon $time, ?string $latitude, ?string $longitude): ?CourierLoaction
    {
        /** @var CourierLoaction|null */
        return CourierLoaction::query()
            ->where('courier_id', $courierId)
            ->where('created_at', '>=', $time->subMinutes(CourierLoaction::DEFAULT_DOWNTIME_MINUTES))
            ->whereRaw('Haversine(latitude, longitude, ?, ?) <= ?', [$latitude, $longitude, CourierLoaction::DEFAULT_DOWNTIME_RADIUS])
            ->orderBy('id')
            ->first();
    }
}
