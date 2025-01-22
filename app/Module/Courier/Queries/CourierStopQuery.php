<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CourierStopQuery as CourierStopQueryContract;
use App\Module\CourierApp\Models\CourierStop;

final class CourierStopQuery implements CourierStopQueryContract
{
    public function getById(int $id): ?CourierStop
    {
        /** @var CourierStop|null */
        return CourierStop::query()
            ->where('id', $id)
            ->first();
    }
}
