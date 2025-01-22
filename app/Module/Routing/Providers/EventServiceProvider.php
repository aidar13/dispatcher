<?php

declare(strict_types=1);

namespace App\Module\Routing\Providers;

use App\Module\Routing\Events\CreateRoutingItemsCreatedEvent;
use App\Module\Routing\Events\RoutingCreatedEvent;
use App\Module\Routing\Listeners\RoutingCreatedListener;
use App\Module\Routing\Listeners\RoutingItemsCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RoutingCreatedEvent::class            => [
            RoutingCreatedListener::class,
        ],
        CreateRoutingItemsCreatedEvent::class => [
            RoutingItemsCreatedListener::class,
        ]
    ];
}
