<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateFastDeliveryOrderCommand;
use App\Module\Order\Contracts\Repositories\CreateFastDeliveryOrderRepository;
use App\Module\Order\Models\FastDeliveryOrder;

final class CreateFastDeliveryOrderHandler
{
    public function __construct(
        private readonly CreateFastDeliveryOrderRepository $repository
    ) {
    }

    public function handle(CreateFastDeliveryOrderCommand $command): void
    {
        $order               = new FastDeliveryOrder();
        $order->container_id = $command->containerId;
        $order->internal_id  = $command->internalOrderId;
        $order->type         = $command->providerId;

        $this->repository->create($order);
    }
}
