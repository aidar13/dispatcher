<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery;
use App\Module\DispatcherSector\Contracts\Services\GetUserEmailService;
use App\Module\Notification\DTO\WebNotificationDTO;
use App\Module\Notification\Events\Integration\SendWebNotificationEvent;
use App\Module\Notification\Models\NotificationType;
use App\Module\Planning\Commands\PartiallyAssembledSendNotificationCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use Illuminate\Support\Facades\Log;

final class PartiallyAssembledSendNotificationHandler
{
    public function __construct(
        private readonly ContainerQuery $containerQuery,
        private readonly DispatchersSectorUserQuery $dispatchersSectorUserQuery,
        private readonly GetUserEmailService $service
    ) {
    }

    public function handle(PartiallyAssembledSendNotificationCommand $command): void
    {
        $container = $this->containerQuery->getById($command->containerId, ['*'], ['sector']);

        $userIds = $this->dispatchersSectorUserQuery->getByDispatcherSectorId($container->sector->dispatcher_sector_id);

        $users = $this->service->getDispatchers($userIds->pluck('user_id')->toArray());

        if ($users->isEmpty()) {
            Log::info('При отправке письма user c gateway не нашлось');
            return;
        }

        $DTO = new WebNotificationDTO();
        $DTO->setObjectId($container->id);
        $DTO->setTypeId(NotificationType::ID_PARTIALLY_ASSEMBLED_CONTAINER);
        $DTO->setUserIds($users->pluck('id')->toArray());
        $DTO->setData([
            'containerName'  => $container->title,
            'containerId'    => $container->id,
            'invoiceNumbers' => $command->partiallyAssembledInvoicesCollection->implode(', ')
        ]);

        event(new SendWebNotificationEvent($DTO));
    }
}
