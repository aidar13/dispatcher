<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\Routing\Commands\DeleteSectorInYandexCommand;
use App\Module\Routing\Contracts\Repositories\IntegrationZoneRepository;

final readonly class DeleteSectorInYandexHandler
{
    public function __construct(
        private SectorQuery $query,
        private IntegrationZoneRepository $repository,
    ) {
    }

    public function handle(DeleteSectorInYandexCommand $command): void
    {
        $sector = $this->query->getById($command->id);

        $this->repository->delete($sector);
    }
}
