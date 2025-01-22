<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Contracts\Queries\CourierLocation;

use App\Module\CourierApp\Models\CourierLoaction;
use Carbon\Carbon;

interface CourierLocationQuery
{
    public function getFirstNearbyLocationByCourierId(int $courierId, Carbon $time, ?string $latitude, ?string $longitude): ?CourierLoaction;
}
