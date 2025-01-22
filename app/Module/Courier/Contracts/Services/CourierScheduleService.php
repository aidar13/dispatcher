<?php

declare(strict_types=1);

namespace App\Module\Courier\Contracts\Services;

use Illuminate\Database\Eloquent\Collection;

interface CourierScheduleService
{
    public function getScheduleByCourierId(int $courierId): Collection;
}
