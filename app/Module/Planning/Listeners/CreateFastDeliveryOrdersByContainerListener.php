<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Events\ContainersSentToAssemblyEvent;
use App\Module\Planning\Models\Container;

final class CreateFastDeliveryOrdersByContainerListener
{
    public function __construct(
        private readonly ContainerQuery $query
    ) {
    }

    public function handle(ContainersSentToAssemblyEvent $event): void
    {
        $containers = $this->query->getFastDeliveryByContainers($event->containerIds);

        $containers->each(fn (Container $container) => dispatch(new CreateFastDeliveryOrderByContainerCommand($container->id)));
    }
}
