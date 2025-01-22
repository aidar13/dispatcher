<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Models\User;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery;
use App\Module\Order\Models\Invoice;
use App\Module\Planning\Contracts\Queries\PlanningQuery;
use App\Module\Routing\Commands\CreateRoutingForDispatcherSectorCommand;
use App\Module\Routing\Commands\SendRoutingCommand;
use App\Module\Routing\Contracts\Repositories\CreateRoutingItemRepository;
use App\Module\Routing\Contracts\Repositories\CreateRoutingRepository;
use App\Module\Routing\Models\Routing;
use App\Module\Routing\Models\RoutingItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

final readonly class CreateRoutingForDispatcherHandler
{
    public function __construct(
        private DispatcherSectorQuery $query,
        private CreateRoutingRepository $repository,
        private PlanningQuery $planningQuery,
    ) {
    }

    public function handle(CreateRoutingForDispatcherSectorCommand $command): void
    {
        $dispatcherSector = $this->query->getById($command->id);
        $date             = now()->format('Y-m-d');

        $items = $this->planningQuery->getInvoicesForRoutingByDispatcherSectorId($dispatcherSector->id, $date);

        if ($items->isEmpty()) {
            Log::info('Нету накладных для маршрутизации по диспетчер сектору ' . $dispatcherSector->id, [
                'date' => $date
            ]);
            return;
        }

        $routing                       = new Routing();
        $routing->type                 = Routing::TYPE_MULTIPLE_CAR;
        $routing->dispatcher_sector_id = $dispatcherSector->id;
        $routing->courier_id           = null;
        $routing->user_id              = User::USER_ADMIN;

        $this->repository->create($routing);

        $this->createItems($routing, $items);

        dispatch(new SendRoutingCommand($routing->id));
    }

    private function createItems(Routing $routing, Collection $items): void
    {
        /** @var CreateRoutingItemRepository $repository */
        $repository = app(CreateRoutingItemRepository::class);

        /** @var Invoice $item */
        foreach ($items as $item) {
            $routingItem              = new RoutingItem();
            $routingItem->type        = RoutingItem::TYPE_DELIVERY;
            $routingItem->client_id   = $item->id;
            $routingItem->client_type = Invoice::class;
            $routingItem->routing_id  = $routing->id;

            $repository->create($routingItem);
        }
    }
}
