<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\SetDeliveryWaitListCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;

final class SetDeliveryWaitListHandler
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly UpdateDeliveryRepository $repository
    ) {
    }

    public function handle(SetDeliveryWaitListCommand $command): void
    {
        $delivery = $this->deliveryQuery->getByInternalId($command->DTO->internalId);

        $delivery->setWaitListStatus($command->DTO->statusId);

        $this->repository->update($delivery);
    }
}
