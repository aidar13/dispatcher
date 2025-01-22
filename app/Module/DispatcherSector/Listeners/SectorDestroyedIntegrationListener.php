<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Listeners;

use App\Module\DispatcherSector\Commands\DestroySectorIntegrationCommand;
use App\Module\DispatcherSector\Events\SectorDestroyedEvent;
use App\Module\Routing\Commands\DeleteSectorInYandexCommand;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;

final readonly class SectorDestroyedIntegrationListener
{
    public function __construct(private SettingsService $settingsService)
    {
    }

    public function handle(SectorDestroyedEvent $event): void
    {
        if (
            $this->settingsService->isEnabled(Settings::YANDEX_SECTOR)
        ) {
            dispatch(new DeleteSectorInYandexCommand($event->sectorId));
        }

        dispatch(new DestroySectorIntegrationCommand($event->sectorId));
    }
}
