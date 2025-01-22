<?php

namespace App\Module\DispatcherSector\Providers;

use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorCreatedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorDestroyedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorUpdatedEvent;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;
use App\Module\DispatcherSector\Listeners\DispatcherSectorClearCacheListener;
use App\Module\DispatcherSector\Listeners\DispatcherSectorCreatedIntegrationListener;
use App\Module\DispatcherSector\Listeners\DispatcherSectorDestroyedIntegrationListener;
use App\Module\DispatcherSector\Listeners\DispatcherSectorDestroyedListener;
use App\Module\DispatcherSector\Listeners\DispatcherSectorUpdatedIntegrationListener;
use App\Module\DispatcherSector\Listeners\SectorCreatedIntegrationListener;
use App\Module\DispatcherSector\Listeners\SectorDestroyedIntegrationListener;
use App\Module\DispatcherSector\Listeners\SectorUpdatedIntegrationListener;
use App\Module\DispatcherSector\Listeners\SendSectorTo1CListener;
use App\Module\DispatcherSector\Listeners\SetSectorCoordinatesListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DispatcherSectorCreatedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            DispatcherSectorCreatedIntegrationListener::class,
        ],
        DispatcherSectorUpdatedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            DispatcherSectorUpdatedIntegrationListener::class,
        ],
        DispatcherSectorDestroyedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            DispatcherSectorDestroyedIntegrationListener::class,
            DispatcherSectorDestroyedListener::class,
        ],
        SectorCreatedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            SectorCreatedIntegrationListener::class,
            SendSectorTo1CListener::class,
            SetSectorCoordinatesListener::class,
        ],
        DefaultSectorCreatedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            SectorCreatedIntegrationListener::class,
            SendSectorTo1CListener::class,
        ],
        SectorUpdatedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            SectorUpdatedIntegrationListener::class,
            SetSectorCoordinatesListener::class,
        ],
        SectorDestroyedEvent::class => [
            DispatcherSectorClearCacheListener::class,
            SectorDestroyedIntegrationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
