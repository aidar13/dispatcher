<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\UpdateRouteSheetCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Queries\RouteSheetQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateRouteSheetRepository;
use App\Module\Delivery\Models\RouteSheet;
use App\Module\Status\Models\StatusType;

final class UpdateRouteSheetHandler
{
    public function __construct(
        private readonly DeliveryQuery $deliveryQuery,
        private readonly RouteSheetQuery $query,
        private readonly UpdateRouteSheetRepository $repository,
    ) {
    }

    public function handle(UpdateRouteSheetCommand $command): void
    {
        $delivery = $this->deliveryQuery->getById($command->deliveryId);

        $routeSheet = $this->query->getByInvoiceId($delivery->invoice_id);

        if ($routeSheet->deliveries->whereNotIn('status_id', StatusType::DELIVERY_DONE_STATUSES)->isEmpty()) {
            $routeSheet->status_id  = RouteSheet::ID_COMPLETED;
        }

        $routeSheet->courier_id = $delivery->courier_id;
        $routeSheet->city_id    = $delivery->city_id;

        $this->repository->update($routeSheet);
    }
}
