<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Helpers\DateHelper;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Delivery\Commands\CreateRouteSheetFrom1CCommand;
use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetRepository;
use App\Module\Delivery\Events\RouteSheetCreatedFromOneCEvent;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Support\Facades\Log;

final class CreateRouteSheetFrom1CHandler
{
    public function __construct(
        private readonly CreateRouteSheetRepository $repository,
        private readonly CourierQuery $courierQuery,
    ) {
    }

    public function handle(CreateRouteSheetFrom1CCommand $command): void
    {
        Log::info('Создание марш листа из 1с', [$command->DTO]);

        $courier = $this->courierQuery->getById($command->DTO->courierId);

        if (!$courier->isStatusActive()) {
            throw new \DomainException("Курьер не активен, courierId: {$courier->id}");
        }

        $routeSheet                       = new RouteSheet();
        $routeSheet->number               = $command->DTO->routeSheetNumber;
        $routeSheet->status_id            = RouteSheet::ID_IN_PROGRESS;
        $routeSheet->date                 = DateHelper::getDateWithTime(now());
        $routeSheet->courier_id           = $courier->id;
        $routeSheet->dispatcher_sector_id = $courier->dispatcher_sector_id;
        $routeSheet->city_id              = $courier->dispatcherSector->city_id;

        $this->repository->create($routeSheet);

        event(new RouteSheetCreatedFromOneCEvent($routeSheet->id, $courier->id));
    }
}
