<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\UpdateSlaCommand;
use App\Module\Order\Contracts\Queries\SlaQuery;
use App\Module\Order\Contracts\Repositories\UpdateSlaRepository;

final class UpdateSlaHandler
{
    public function __construct(
        private readonly UpdateSlaRepository $repository,
        private readonly SlaQuery $query
    ) {
    }

    public function handle(UpdateSlaCommand $command): void
    {
        $sla = $this->query->getById($command->DTO->id);

        $sla->city_from        = $command->DTO->cityFrom;
        $sla->city_to          = $command->DTO->cityTo;
        $sla->pickup           = $command->DTO->pickup;
        $sla->processing       = $command->DTO->processing;
        $sla->transit          = $command->DTO->transit;
        $sla->delivery         = $command->DTO->delivery;
        $sla->shipment_type_id = $command->DTO->shipmentTypeId;

        $this->repository->update($sla);
    }
}
