<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\Eloquent;

use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetRepository;
use App\Module\Delivery\Contracts\Repositories\UpdateRouteSheetRepository;
use App\Module\Delivery\Models\RouteSheet;
use Throwable;

final class RouteSheetRepository implements CreateRouteSheetRepository, UpdateRouteSheetRepository
{
    /**
     * @throws Throwable
     */
    public function create(RouteSheet $routeSheet): void
    {
        $routeSheet->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(RouteSheet $routeSheet): void
    {
        $routeSheet->saveOrFail();
    }
}
