<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Exceptions\DomainExceptionWithErrors;
use App\Module\Planning\Commands\SendContainersToAssemblyCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\Integration\SendToAssemblyRepository;
use App\Module\Planning\Events\ContainersSentToAssemblyEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SendContainersToAssemblyHandler
{
    public function __construct(
        private readonly ContainerQuery $query,
        private readonly SendToAssemblyRepository $sendToAssemblyRepository
    ) {
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    public function handle(SendContainersToAssemblyCommand $command): void
    {
        $containers = $this->query->getAllContainersToAssembly($command->DTO);

        try {
            DB::transaction(function () use ($containers, $command) {
                $oneCContainers = $this->sendToAssemblyRepository->send($containers);

                event(new ContainersSentToAssemblyEvent(
                    $containers->pluck('id')->toArray(),
                    $oneCContainers
                ));
            });
        } catch (Throwable $exception) {
            Log::info('Не удалось отправить контейнеры на сборку: ', [
                'containerIds' => $containers->pluck('id')->toArray(),
                'message'      => $exception->getMessage()
            ]);

            throw new DomainExceptionWithErrors('Не удалось отправить контейнеры на сборку! ' . $exception->getMessage());
        }
    }
}
