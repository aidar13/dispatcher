<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\DispatcherSector\Contracts\Integrations\Repositories\SendSectorTo1CRepository;
use App\Module\DispatcherSector\DTO\Integration\IntegrationSector1CDTO;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

final class SendSectorsTo1CCommand extends Command
{
    public function __construct(
        private readonly SendSectorTo1CRepository $repository
    ) {
        parent::__construct();
    }

    protected $signature = 'command:send-sectors-1c';

    protected $description = 'Создание секторов в 1С';

    public function handle(): void
    {
        $object = Sector::query();
        $count  = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar) {
            $collection->each(function (Sector $sector) use ($progressBar) {
                try {
                    $this->repository->sendSectorTo1C(IntegrationSector1CDTO::fromModel($sector));

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("id=$sector->id " . $exception->getMessage());
                }
            });
        });

        $progressBar->finish();
    }
}
