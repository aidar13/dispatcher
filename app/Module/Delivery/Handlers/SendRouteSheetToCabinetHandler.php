<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\SendRouteSheetToCabinetCommand;
use App\Module\Delivery\Contracts\Queries\RouteSheetQuery;
use App\Module\Delivery\Contracts\Repositories\Integration\CreateDeliveriesInCabinetRepository;

final class SendRouteSheetToCabinetHandler
{
    public function __construct(
        private readonly RouteSheetQuery $query,
        private readonly CreateDeliveriesInCabinetRepository $cabinetRepository
    ) {
    }

    public function handle(SendRouteSheetToCabinetCommand $command): void
    {
        $routeSheet = $this->query->getById($command->routeSheetId);

        $this->cabinetRepository->createDeliveries(
            $routeSheet->number,
            $routeSheet->courier_id,
        );
    }
}
