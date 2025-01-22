<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\Planning\Commands\CreateContainerFromRoutingCommand;
use App\Module\Routing\Commands\UpdateRoutingItemPositionsCommand;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository;
use App\Module\Routing\Contracts\Repositories\UpdateRoutingRepository;
use App\Module\Routing\Models\Routing;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

final class CheckMVRPRoutingStatusCommand extends Command
{
    protected $signature = 'mvrp-routing:check-status';

    protected $description = 'Проверить статус яндекс маршрутизации для нескольких курьеров (MVRP)';

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

        /** @var UpdateRoutingRepository $routingRepository */
        $routingRepository = app(UpdateRoutingRepository::class);
        $object = Routing::query()
            ->where('type', Routing::TYPE_MULTIPLE_CAR)
            ->whereNull('response')
            ->whereNull('courier_id')
            ->whereNotNull('task_id');

        $count  = $object->count();

        $this->info("Найдено $count");
        $progressBar = $this->output->createProgressBar($count);

        /** @var IntegrationRoutingRepository $integrationRepository */
        $integrationRepository = app(IntegrationRoutingRepository::class);

        $object->chunk(100, function (Collection $collection) use ($progressBar, $integrationRepository, $routingRepository) {
            $collection->each(function (Routing $routing) use ($progressBar, $integrationRepository, $routingRepository) {
                try {
                    $routingDTO = $integrationRepository->getByTaskId($routing->task_id);

                    if ($routingDTO->statusCode !== 200) {
                        $this->info("Задача еще не готова для $routing->id \n");

                        return;
                    }

                    $routing->response = json_encode($routingDTO);
                    $routingRepository->update($routing);

                    $this->info("Проверен статус для $routing->id \n");

                    dispatch(new CreateContainerFromRoutingCommand($routing->id));
                    dispatch(new UpdateRoutingItemPositionsCommand($routing->id));

                    $progressBar->advance();
                } catch (\Throwable $exception) {
                    $this->error("id=$routing->id " . $exception->getMessage());
                }
            });
        });

        $progressBar->finish();
    }
}
