<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories;

use App\Module\Delivery\Models\RouteSheet;

interface UpdateRouteSheetRepository
{
    public function update(RouteSheet $routeSheet): void;
}
