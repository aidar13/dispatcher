<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\UpdateSectorIntegrationCommand;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\UpdateSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\DTO\IntegrationSectorDTO;

final class UpdateSectorIntegrationHandler
{
    public function __construct(
        private readonly SectorQuery $sectorQuery,
        private readonly UpdateSectorIntegrationRepository $sectorIntegrationRepository
    ) {
    }

    public function handle(UpdateSectorIntegrationCommand $command): void
    {
        $sector = $this->sectorQuery->getById($command->sectorId);

        $this->sectorIntegrationRepository->update(IntegrationSectorDTO::fromModel($sector));
    }
}
