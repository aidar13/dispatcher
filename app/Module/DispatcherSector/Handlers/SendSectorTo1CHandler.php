<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\SendSectorTo1CCommand;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\SendSectorTo1CRepository;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\DTO\Integration\IntegrationSector1CDTO;

final class SendSectorTo1CHandler
{
    public function __construct(
        private readonly SendSectorTo1CRepository $repository,
        private readonly SectorQuery $query
    ) {
    }

    public function handle(SendSectorTo1CCommand $command): void
    {
        $sector = $this->query->getById($command->sectorId);

        $this->repository->sendSectorTo1C(IntegrationSector1CDTO::fromModel($sector));
    }
}
