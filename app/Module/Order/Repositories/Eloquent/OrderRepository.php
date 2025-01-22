<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateOrderRepository;
use App\Module\Order\Contracts\Repositories\UpdateOrderRepository;
use App\Module\Order\Models\Order;

final class OrderRepository implements CreateOrderRepository, UpdateOrderRepository
{
    public function create(Order $order): void
    {
        $order->save();
    }

    /**
     * @throws \Throwable
     */
    public function update(Order $order): void
    {
        $order->saveOrFail();
    }
}
