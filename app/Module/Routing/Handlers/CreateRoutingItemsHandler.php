<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\Routing\Commands\CreateRoutingItemsCommand;
use App\Module\Routing\Contracts\Queries\RoutingQuery;
use App\Module\Routing\Contracts\Repositories\CreateRoutingItemRepository;
use App\Module\Routing\Contracts\Services\RoutingService;
use App\Module\Routing\DTO\RoutingItemDTO;
use App\Module\Routing\Events\CreateRoutingItemsCreatedEvent;
use App\Module\Routing\Models\RoutingItem;

final readonly class CreateRoutingItemsHandler
{
    public function __construct(
        private RoutingQuery $query,
        private RoutingService $service,
        private CreateRoutingItemRepository $repository,
    ) {
    }

    public function handle(CreateRoutingItemsCommand $command): void
    {
        $routing = $this->query->getById($command->routingId);
        $items   = $this->service->getAllByCourierId($routing->courier_id);

        /** @var RoutingItemDTO $item */
        foreach ($items as $item) {
            $routingItem              = new RoutingItem();
            $routingItem->type        = $item->type;
            $routingItem->client_id   = $item->clientId;
            $routingItem->client_type = $item->clientType;
            $routingItem->routing_id  = $routing->id;

            $this->repository->create($routingItem);
        }

        event(new CreateRoutingItemsCreatedEvent($routing->id));
    }
}
