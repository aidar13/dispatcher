<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\UpdateSectorIntegrationCommand;
use App\Module\DispatcherSector\Events\SectorUpdatedEvent;
use App\Module\Routing\Commands\UpdateSectorInYandexCommand;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;

final readonly class SectorUpdatedIntegrationListener
{
    public function __construct(private SettingsService $settingsService)
    {
    }

    public function handle(SectorUpdatedEvent $event): void
    {
        if (
            $this->settingsService->isEnabled(Settings::YANDEX_SECTOR)
        ) {
            dispatch(new UpdateSectorInYandexCommand($event->sectorId));
        }

        dispatch(new UpdateSectorIntegrationCommand($event->sectorId));
    }
}
