<?php

declare(strict_types=1);

namespace App\Module\Routing\Repositories;

use App\Module\Routing\Contracts\Repositories\CreateRoutingItemRepository;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingItemRepository;
use App\Module\Routing\Models\RoutingItem;
use Throwable;

final class RoutingItemRepository implements UpdateRoutingItemRepository, CreateRoutingItemRepository
{
    /**
     * @throws Throwable
     */
    public function create(RoutingItem $model): void
    {
        $model->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(RoutingItem $model): void
    {
        $model->updateOrFail();
    }
}
