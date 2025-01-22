<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use App\Module\Routing\Commands\UpdateRoutingItemPositionsCommand;
use App\Module\Routing\Contracts\Queries\RoutingQuery;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingItemRepository;
use App\Module\Routing\DTO\IntegrationRoutingItemDTO;
use App\Module\Routing\Models\Routing;
use App\Module\Routing\Models\RoutingItem;

final readonly class UpdateRoutingItemPositionsHandler
{
    public function __construct(
        private RoutingQuery $query,
        private IntegrationRoutingRepository $routingRepository,
        private UpdateRoutingItemRepository $routingItemRepository,
    ) {
    }

    public function handle(UpdateRoutingItemPositionsCommand $command): void
    {
        $routing    = $this->query->getById($command->routingId);
        $routingDTO = $this->routingRepository->getByTaskId($routing->task_id);
        $routes     = $routingDTO->routes[0];

        /** @var IntegrationRoutingItemDTO $routes */
        foreach ($routes->route as $position => $route) {
            if ($route['node']['type'] !== 'location') {
                continue;
            }

            $clientId   = $route['node']['value']['client_id'];
            $type       = $route['node']['value']['type'];
            $clientType = $type === Routing::TYPE_TAKE
                ? Order::class
                : Invoice::class;

            /** @var RoutingItem $item */
            $item = $routing->items()
                ->where('client_id', $clientId)
                ->where('client_type', $clientType)
                ->first();

            if (!$item) {
                continue;
            }

            $item->position = $position;

            $this->routingItemRepository->update($item);
        }
    }
}
