<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\Routing\Commands\CreateRoutingForDispatcherSectorCommand;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CreateMVRPYandexRoutingCommand extends Command
{
    protected $signature = 'routing:create';

    protected $description = 'Сформировать контейнеры через яндекс маршрутизацию';

    public function __construct(
        private readonly SettingsService $settingsService,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        if (!$this->settingsService->isEnabled(Settings::YANDEX_ROUTING)) {
            return;
        }

        $object = DispatcherSector::query()
            ->whereHas('couriers', function (Builder $query) {
                $query->where('routing_enabled', true);
            });

        $count = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar) {
            $collection->each(function (DispatcherSector $dispatcherSector) use ($progressBar) {
                try {
                    dispatch_sync(new CreateRoutingForDispatcherSectorCommand($dispatcherSector->id));

                    $this->info("Сформирован для $dispatcherSector->id \n");

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("id=$dispatcherSector->id " . $exception->getMessage());
                }
            });
        });

        $progressBar->finish();
    }
}
