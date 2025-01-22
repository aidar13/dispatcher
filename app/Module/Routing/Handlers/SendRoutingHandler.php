<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\Routing\Commands\SendRoutingCommand;
use App\Module\Routing\Contracts\Queries\RoutingQuery;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository;

final readonly class SendRoutingHandler
{
    public function __construct(
        private RoutingQuery $query,
        private IntegrationRoutingRepository $repository,
        private HttpWarehouseQuery $warehouseQuery,
    ) {
    }

    public function handle(SendRoutingCommand $command): void
    {
        $routing = $this->query->getById($command->routingId);

        if ($routing->task_id) {
            return;
        }

        $warehouse = $this->warehouseQuery->getByCityId($routing->courier->dispatcherSector->city_id ?? $routing->dispatcherSector->city_id);

        if (!$warehouse) {
            return;
        }

        $this->repository->create($routing, $warehouse);
    }
}
