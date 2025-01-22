<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Queries;

use Illuminate\Database\Eloquent\Collection;

interface CourierScheduleQuery
{
    public function getScheduleByCourierId(int $courierId): Collection;
}
