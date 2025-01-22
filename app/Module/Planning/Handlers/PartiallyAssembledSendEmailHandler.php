<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery;
use App\Module\DispatcherSector\Contracts\Services\GetUserEmailService;
use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Notification\DTO\EmailNotificationDTO;
use App\Module\Planning\Commands\PartiallyAssembledSendEmailCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use Illuminate\Support\Facades\Log;

final class PartiallyAssembledSendEmailHandler
{
    public function __construct(
        private readonly SendEmailNotificationRepository $repository,
        private readonly ContainerQuery $containerQuery,
        private readonly DispatchersSectorUserQuery $dispatchersSectorUserQuery,
        private readonly GetUserEmailService $service
    ) {
    }

    public function handle(PartiallyAssembledSendEmailCommand $command): void
    {
        $container = $this->containerQuery->getById($command->containerId, ['*'], ['sector']);

        $userIds = $this->dispatchersSectorUserQuery->getByDispatcherSectorId($container->sector->dispatcher_sector_id);

        $users = $this->service->getDispatchers($userIds->pluck('user_id')->toArray());

        if ($users->isEmpty()) {
            Log::info('При отправке письма user c gateway не нашлось');
            return;
        }

        $DTO = new EmailNotificationDTO();

        $DTO->setEmails($users->pluck('email')->toArray());

        $DTO->setSubject(__('email.container.assembled.subject', [
            'containerName'  => $container->title,
            'containerId'    => $container->id
        ]));

        $DTO->setContent(__('email.container.assembled.content', [
            'containerName'  => $container->title,
            'containerId'    => $container->id,
            'invoiceNumbers' => $command->partiallyAssembledInvoicesCollection->implode(', ')
        ]));

        $this->repository->send($DTO);
    }
}
