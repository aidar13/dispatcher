<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\OrderTake;

use App\Module\CourierApp\Events\OrderTake\TakeWaitListStatusChangedEvent;
use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery;
use App\Module\Notification\DTO\WebNotificationDTO;
use App\Module\Notification\Events\Integration\SendWebNotificationEvent;
use App\Module\Notification\Models\NotificationType;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;

final class SendTakeInfoWaitListNotificationListener
{
    public function __construct(
        private readonly OrderTakeQuery $query,
        private readonly DispatchersSectorUserQuery $dispatchersSectorQuery,
    ) {
    }

    public function handle(TakeWaitListStatusChangedEvent $event): void
    {
        $takeInfo  = $this->query->getById($event->takeId);
        $dsUserIds = $this->dispatchersSectorQuery->getAllDispatcherSectorUserIdsByCityId($takeInfo->city_id);
        $order     = $takeInfo->invoice->order;

        $dto = new WebNotificationDTO();
        $dto->setObjectId($order->id);
        $dto->setTypeId(NotificationType::ID_TAKE_WAIT_LIST_STATUS_CREATED);
        $dto->setUserIds($dsUserIds);
        $dto->setData([
            'statusCode'  => $event->DTO->statusCode,
            'statusName'  => $takeInfo->waitListStatus?->name,
            'orderNumber' => $order->number
        ]);

        event(new SendWebNotificationEvent($dto));
    }
}
