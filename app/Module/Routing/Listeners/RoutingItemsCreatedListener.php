<?php

declare(strict_types=1);

namespace App\Module\Routing\Listeners;

use App\Module\Routing\Commands\SendRoutingCommand;
use App\Module\Routing\Events\CreateRoutingItemsCreatedEvent;

final class RoutingItemsCreatedListener
{
    public function handle(CreateRoutingItemsCreatedEvent $event): void
    {
        dispatch(new SendRoutingCommand(
            $event->routingId
        ));
    }
}
