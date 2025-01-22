<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Helpers\DateHelper;
use App\Module\Delivery\Commands\CreateRouteSheetCommand;
use App\Module\Delivery\Commands\CreateRouteSheetInvoiceCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Queries\RouteSheetQuery;
use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetRepository;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Support\Facades\Log;

final class CreateRouteSheetHandler
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly RouteSheetQuery $query,
        private readonly CreateRouteSheetRepository $repository,
    ) {
    }

    public function handle(CreateRouteSheetCommand $command): void
    {
        $delivery = $this->deliveryQuery->getByInvoiceId($command->invoiceId);

        if (!$delivery) {
            Log::info("Не найдена доставка при создании RouteSheet с invoiceId = $command->invoiceId");
            return;
        }

        $routeSheet = $this->query->getByRouteSheetNumber($command->routeSheetNumber);

        if ($routeSheet) {
            dispatch(new CreateRouteSheetInvoiceCommand($routeSheet->id, $delivery->invoice_id));

            return;
        }

        $routeSheet                       = new RouteSheet();
        $routeSheet->number               = $command->routeSheetNumber;
        $routeSheet->status_id            = RouteSheet::ID_IN_PROGRESS;
        $routeSheet->date                 = DateHelper::getDateWithTime($delivery->created_at);
        $routeSheet->courier_id           = $delivery->courier_id;
        $routeSheet->dispatcher_sector_id = $delivery->invoice?->receiver?->dispatcher_sector_id;
        $routeSheet->city_id              = $delivery->city_id;

        $this->repository->create($routeSheet);

        dispatch(new CreateRouteSheetInvoiceCommand($routeSheet->id, $delivery->invoice_id));
    }
}
