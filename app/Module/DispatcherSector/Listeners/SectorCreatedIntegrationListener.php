<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\CreateSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\DefaultSectorCreatedEvent;
use App\Module\DispatcherSector\Events\SectorCreatedEvent;
use App\Module\Routing\Commands\CreateSectorInYandexCommand;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;

final readonly class SectorCreatedIntegrationListener
{
    public function __construct(private SettingsService $settingsService)
    {
    }

    public function handle(SectorCreatedEvent|DefaultSectorCreatedEvent $event): void
    {
        if (
            $event instanceof SectorCreatedEvent &&
            $this->settingsService->isEnabled(Settings::YANDEX_SECTOR)
        ) {
            dispatch(new CreateSectorInYandexCommand($event->sectorId));
        }

        dispatch(new CreateSectorIntegrationCommand($event->sectorId));
    }
}
