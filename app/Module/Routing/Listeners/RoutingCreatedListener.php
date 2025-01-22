<?php

declare(strict_types=1);

namespace App\Module\Routing\Listeners;

use App\Module\Routing\Commands\CreateRoutingItemsCommand;
use App\Module\Routing\Events\RoutingCreatedEvent;

final class RoutingCreatedListener
{
    public function handle(RoutingCreatedEvent $event): void
    {
        dispatch(new CreateRoutingItemsCommand(
            $event->routingId
        ));
    }
}
