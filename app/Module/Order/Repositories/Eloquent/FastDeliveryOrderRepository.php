<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateFastDeliveryOrderRepository;
use App\Module\Order\Contracts\Repositories\UpdateFastDeliveryOrderRepository;
use App\Module\Order\Models\FastDeliveryOrder;
use Throwable;

final class FastDeliveryOrderRepository implements CreateFastDeliveryOrderRepository, UpdateFastDeliveryOrderRepository
{
    /**
     * @throws Throwable
     */
    public function create(FastDeliveryOrder $order): void
    {
        $order->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(FastDeliveryOrder $order): void
    {
        $order->saveOrFail();
    }
}
