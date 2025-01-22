<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateSlaCommand;
use App\Module\Order\Contracts\Queries\SlaQuery;
use App\Module\Order\Contracts\Repositories\CreateSlaRepository;
use App\Module\Order\Models\Sla;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateSlaHandler
{
    public function __construct(
        private readonly CreateSlaRepository $createSlaRepository,
        private readonly SlaQuery $slaQuery
    ) {
    }

    public function handle(CreateSlaCommand $command): void
    {
        if ($this->slaExists($command->DTO->id)) {
            return;
        }

        $sla                   = new Sla();
        $sla->id               = $command->DTO->id;
        $sla->city_from        = $command->DTO->cityFrom;
        $sla->city_to          = $command->DTO->cityTo;
        $sla->pickup           = $command->DTO->pickup;
        $sla->processing       = $command->DTO->processing;
        $sla->transit          = $command->DTO->transit;
        $sla->delivery         = $command->DTO->delivery;
        $sla->shipment_type_id = $command->DTO->shipmentTypeId;

        $this->createSlaRepository->create($sla);
    }

    private function slaExists(int $id): bool
    {
        try {
            $this->slaQuery->getById($id);

            return true;
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
