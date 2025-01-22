<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\UpdateFastDeliveryOrderCommand;
use App\Module\Order\Contracts\Queries\FastDeliveryOrderQuery;
use App\Module\Order\Contracts\Repositories\UpdateFastDeliveryOrderRepository;

final class UpdateFastDeliveryOrderHandler
{
    public function __construct(
        private readonly UpdateFastDeliveryOrderRepository $repository,
        private readonly FastDeliveryOrderQuery $query
    ) {
    }

    public function handle(UpdateFastDeliveryOrderCommand $command): void
    {
        $order                  = $this->query->findByContainerId($command->containerId);
        $order->internal_id     = $command->DTO->internalOrderId;
        $order->price           = $command->DTO->price;
        $order->internal_status = $command->DTO->internalStatus;

        $this->repository->update($order);
    }
}
