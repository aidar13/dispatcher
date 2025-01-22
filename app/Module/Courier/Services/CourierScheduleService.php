<?php

declare(strict_types=1);

namespace App\Module\Courier\Services;

use App\Module\Courier\Contracts\Queries\CourierScheduleQuery;
use App\Module\Courier\Contracts\Services\CourierScheduleService as CourierScheduleServiceContract;
use Illuminate\Database\Eloquent\Collection;

final class CourierScheduleService implements CourierScheduleServiceContract
{
    public function __construct(
        private readonly CourierScheduleQuery $query
    ) {
    }

    public function getScheduleByCourierId(int $courierId): Collection
    {
        return $this->query->getScheduleByCourierId($courierId);
    }
}
