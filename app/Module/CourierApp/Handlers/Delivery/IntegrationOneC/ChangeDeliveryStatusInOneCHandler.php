<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery\IntegrationOneC;

use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;

final class ChangeDeliveryStatusInOneCHandler
{
    public function __construct(
        private readonly DeliveryQuery $query,
        private readonly UpdateDeliveryRepository $repository,
    ) {
    }

    public function handle(ChangeDeliveryStatusInOneCCommand $command): void
    {
        $delivery = $this->query->getById($command->id);

        $this->repository->update($delivery);
    }
}
