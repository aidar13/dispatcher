<?php

declare(strict_types=1);

namespace App\Module\Routing\Repositories;

use App\Module\Routing\Contracts\Repositories\CreateRoutingRepository;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingRepository;
use App\Module\Routing\Models\Routing;
use Throwable;

final class RoutingRepository implements CreateRoutingRepository, UpdateRoutingRepository
{
    /**
     * @throws Throwable
     */
    public function create(Routing $model): void
    {
        $model->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Routing $model): void
    {
        $model->updateOrFail();
    }
}
