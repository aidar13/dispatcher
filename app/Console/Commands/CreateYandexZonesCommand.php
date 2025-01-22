<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\DispatcherSector\Models\Sector;
use App\Module\Routing\Commands\CreateSectorInYandexCommand;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CreateYandexZonesCommand extends Command
{
    protected $signature = 'sectors:create-in-yandex
        {dispatcherSectorId? : id сектора диспетчера}';

    protected $description = 'Создать сектора в яндексе';

    public function handle(): void
    {
        $dispatcherSectorId = $this->argument('dispatcherSectorId')
            ? (int)$this->argument('dispatcherSectorId')
            : null;

        $object = Sector::query()
            ->when($dispatcherSectorId, function (Builder $builder) use ($dispatcherSectorId) {
                $builder->where('dispatcher_sector_id', $dispatcherSectorId);
            });

        $count = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar) {
            $collection->each(function (Sector $sector) use ($progressBar) {
                try {
                    dispatch_sync(new CreateSectorInYandexCommand($sector->id));

                    $this->info("sectorID $sector->id created \n");

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("Cannot create sectorID $sector->id " . $exception->getMessage() . "\n");
                }
            });
        });

        $progressBar->finish();
    }
}
