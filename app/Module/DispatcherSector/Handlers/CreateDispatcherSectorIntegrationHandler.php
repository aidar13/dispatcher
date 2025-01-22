<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Handlers;

use App\Module\DispatcherSector\Commands\CreateDispatcherSectorIntegrationCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\DispatcherSector\DTO\IntegrationDispatcherSectorDTO;
use App\Module\DispatcherSector\Events\DispatcherSectorCreatedIntegrationEvent;
use App\Module\DispatcherSector\Models\DispatcherSector;

final readonly class CreateDispatcherSectorIntegrationHandler
{
    public function __construct(
        private DispatcherSectorQuery $dispatcherSectorQuery
    ) {
    }

    public function handle(CreateDispatcherSectorIntegrationCommand $command): void
    {
        $dispatcherSector = $this->dispatcherSectorQuery->getById($command->dispatcherSectorId);

        $DTO = IntegrationDispatcherSectorDTO::fromModel($dispatcherSector);
        $DTO->setDispatcherIds($this->getDispatcherIds($dispatcherSector));

        event(new DispatcherSectorCreatedIntegrationEvent($DTO));
    }

    private function getDispatcherIds(DispatcherSector $sector): array
    {
        return $sector->dispatcherSectorUsers->pluck('user_id')?->toArray() ?? [];
    }
}
