<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\SetFastDeliveryCourierCommand;
use App\Module\Order\Contracts\Queries\FastDeliveryOrderQuery;
use App\Module\Order\Contracts\Repositories\UpdateFastDeliveryOrderRepository;

final class SetFastDeliveryCourierHandler
{
    public function __construct(
        private readonly FastDeliveryOrderQuery $query,
        private readonly UpdateFastDeliveryOrderRepository $repository
    ) {
    }

    public function handle(SetFastDeliveryCourierCommand $command): void
    {
        $order = $this->query->findByInternalId($command->internalId);

        $order->courier_name    = $command->DTO->courierName;
        $order->courier_phone   = $command->DTO->courierPhone;
        $order->tracking_url    = $command->DTO->trackingUrl;
        $order->internal_status = $command->DTO->internalStatus;
        $order->price           = $command->DTO->price;

        $this->repository->update($order);
    }
}
