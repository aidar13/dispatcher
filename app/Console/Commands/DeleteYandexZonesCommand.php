<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\DispatcherSector\Models\Sector;
use App\Module\Routing\Commands\DeleteSectorInYandexCommand;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

final class DeleteYandexZonesCommand extends Command
{
    protected $signature = 'sectors:delete-in-yandex';

    protected $description = 'Удалить сектора в яндексе';

    public function handle(): void
    {
        $object = Sector::query();

        $count = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar) {
            $collection->each(function (Sector $sector) use ($progressBar) {
                try {
                    dispatch_sync(new DeleteSectorInYandexCommand($sector->id));

                    $this->info("SectorID $sector->id deleted \n");

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("Cannot delete sectorID $sector->id " . $exception->getMessage() . "\n");
                }
            });
        });

        $progressBar->finish();
    }
}
