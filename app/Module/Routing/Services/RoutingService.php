<?php

declare(strict_types=1);

namespace App\Module\Routing\Services;

use App\Module\CourierApp\Contracts\Queries\OrderTake\CourierOrderTakeQuery;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Models\Container;
use App\Module\Routing\Contracts\Services\RoutingService as RoutingServiceContract;
use App\Module\Routing\DTO\RoutingItemDTO;
use App\Module\Take\Models\OrderTake;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

final readonly class RoutingService implements RoutingServiceContract
{
    public function __construct(
        private CourierOrderTakeQuery $takeQuery,
        private ContainerQuery $containerQuery,
    ) {
    }

    public function getAllByCourierId(int $courierId): Collection
    {
        $takes    = $this->takeQuery->getAllByCourierId($courierId);
        $invoices = $this->getInvoices($courierId);

        $items = [];

        /** @var OrderTake $take */
        foreach ($takes as $take) {
            $items[] = RoutingItemDTO::fromTake($take);
        }

        /** @var Invoice $invoices */
        foreach ($invoices as $invoice) {
            $items[] = RoutingItemDTO::fromInvoice($invoice);
        }

        return collect($items);
    }

    private function getInvoices(int $courierId): EloquentCollection|array
    {
        /** @var Container|null $container */
        $container = $this->containerQuery->getByCourierIdForRouting($courierId);

        return $container?->invoices ?? [];
    }
}
