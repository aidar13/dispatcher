<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateAdditionalServiceValueCommand;
use App\Module\Order\Contracts\Queries\AdditionalServiceValueQuery;
use App\Module\Order\Contracts\Repositories\CreateAdditionalServiceValueRepository;
use App\Module\Order\Models\AdditionalServiceValue;
use App\Module\Order\Models\Invoice;

final readonly class CreateAdditionalServiceValueHandler
{
    public function __construct(
        private AdditionalServiceValueQuery $query,
        private CreateAdditionalServiceValueRepository $repository
    ) {
    }

    public function handle(CreateAdditionalServiceValueCommand $command): void
    {
        $service = $this->query->getById($command->DTO->id);

        if ($service) {
            return;
        }

        $service                      = new AdditionalServiceValue();
        $service->id                  = $command->DTO->id;
        $service->type_id             = $command->DTO->typeId;
        $service->status_id           = $command->DTO->statusId;
        $service->client_id           = $command->DTO->clientId;
        $service->client_type         = Invoice::class;
        $service->value               = $command->DTO->value;
        $service->duration            = $command->DTO->duration;
        $service->cost_per_hour       = $command->DTO->costPerHour;
        $service->cost_total          = $command->DTO->costTotal;
        $service->paid_price_per_hour = $command->DTO->paidPricePerHour;
        $service->paid_price_total    = $command->DTO->paidPriceTotal;
        $service->carrier_id          = $command->DTO->carrierId;
        $service->setCreatedAt($command->DTO->createdAt);
        $service->setUpdatedAt($command->DTO->updatedAt);

        $this->repository->create($service);
    }
}
