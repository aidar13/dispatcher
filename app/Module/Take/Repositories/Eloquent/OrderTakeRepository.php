<?php

declare(strict_types=1);

namespace App\Module\Take\Repositories\Eloquent;

use App\Module\Take\Contracts\Repositories\CreateOrderTakeRepository;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\Models\OrderTake;
use Throwable;

final class OrderTakeRepository implements CreateOrderTakeRepository, UpdateOrderTakeRepository
{
    /**
     * @throws Throwable
     */
    public function create(OrderTake $take): void
    {
        $take->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(OrderTake $take): void
    {
        $take->updateOrFail();
    }
}
