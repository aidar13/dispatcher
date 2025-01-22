<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Constants\CacheConstants;
use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorCreatedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorDestroyedEvent;
use App\Module\DispatcherSector\Events\DispatcherSectorUpdatedEvent;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;
use Illuminate\Support\Facades\Cache;

final class DispatcherSectorClearCacheListener
{
    public function handle(
        DispatcherSectorCreatedEvent|
        DispatcherSectorUpdatedEvent|
        DispatcherSectorDestroyedEvent|
        SectorCreatedEvent|
        DefaultSectorCreatedEvent|
        SectorDestroyedEvent|
        SectorUpdatedEvent $event
    ): void {
        Cache::forget(CacheConstants::DISPATCHER_SECTOR_ALL_CACHE_KEY);
    }
}
