<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Services;

use Illuminate\Support\Collection;

interface RoutingService
{
    public function getAllByCourierId(int $courierId): Collection;
}
