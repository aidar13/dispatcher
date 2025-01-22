<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Module\RabbitMQ\Contracts\Repositories\ForceDeleteRabbitMQRequestRepository;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

final class DeleteRabbitMQRequest extends Command
{
    protected $signature = 'rabbitmq:delete
        {startDate? : Дата с в формате Y-m-d}
        {endDate? : Дата до в формате Y-m-d}';

    protected $description = 'Удаление успешно выполненных из таблицы rabbit_mq_requests';

    public function handle(): void
    {
        /** @var ForceDeleteRabbitMQRequestRepository $repository */
        $repository = app(ForceDeleteRabbitMQRequestRepository::class);

        $startDate = $this->argument('startDate') ?: now()->subMonths(2)->format('Y-m-d');
        $endDate   = $this->argument('endDate') ?: now()->subMonth()->format('Y-m-d');

        $object = RabbitMQRequest::query()
            ->whereNotNull('success_at')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<', $endDate);

        $count = $object->count();

        $this->info("Найдено: $count успешно выполненных");
        $progressBar = $this->output->createProgressBar($count);

        $object->chunk(100, function (Collection $collection) use ($progressBar, $repository) {
            $collection->each(function (RabbitMQRequest $request) use ($progressBar, $repository) {
                $repository->forceDelete($request);
                $progressBar->advance();
            });
        });

        $progressBar->finish();
    }
}
