<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\DestroySectorIntegrationCommand;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\DestroySectorIntegrationRepository;

final class DestroySectorIntegrationHandler
{
    public function __construct(
        private readonly DestroySectorIntegrationRepository $sectorIntegrationRepository
    ) {
    }

    public function handle(DestroySectorIntegrationCommand $command): void
    {
        $this->sectorIntegrationRepository->destroy($command->sectorId);
    }
}
