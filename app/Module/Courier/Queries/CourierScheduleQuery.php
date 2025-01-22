<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CourierScheduleQuery as CourierScheduleQueryContract;
use App\Module\Courier\Models\CourierSchedule;
use Illuminate\Database\Eloquent\Collection;

final class CourierScheduleQuery implements CourierScheduleQueryContract
{
    /**
     * @psalm-suppress InvalidReturnStatement
     * @psalm-suppress InvalidReturnType
     * @param int $courierId
     * @return Collection
     */
    public function getScheduleByCourierId(int $courierId): Collection
    {
        return CourierSchedule::query()
            ->where('courier_id', $courierId)
            ->get();
    }
}
