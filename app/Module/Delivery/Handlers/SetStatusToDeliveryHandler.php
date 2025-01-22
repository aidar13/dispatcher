<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\SetStatusToDeliveryCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Events\DeliveryStatusUpdatedEvent;

final class SetStatusToDeliveryHandler
{
    public function __construct(
        private readonly DeliveryQuery $query,
        private readonly UpdateDeliveryRepository $repository
    ) {
    }

    public function handle(SetStatusToDeliveryCommand $command): void
    {
        $delivery = $this->query->getById($command->deliveryId);

        $delivery->setStatusId($command->statusId);

        $this->repository->update($delivery);

        event(new DeliveryStatusUpdatedEvent($delivery->id));
    }
}
