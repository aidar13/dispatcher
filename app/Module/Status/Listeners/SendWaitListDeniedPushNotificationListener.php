<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\DTO\PushNotificationDTO;
use App\Module\Order\Models\Order;
use App\Module\Status\Contracts\Queries\WaitListStatusQuery;
use App\Module\Status\Events\WaitListStatusCreatedEvent;
use App\Module\Status\Models\WaitListStatus;

final readonly class SendWaitListDeniedPushNotificationListener
{
    public function __construct(
        private WaitListStatusQuery $query,
        private SendPushNotificationRepository $repository,
    ) {
    }

    public function handle(WaitListStatusCreatedEvent $event): void
    {
        $waitList = $this->query->getById($event->id);

        if (!$waitList->isStateDenied()) {
            return;
        }

        $DTO = new PushNotificationDTO();
        $DTO->setUserId($waitList->parent->user_id);
        $DTO->setTitle(__('notification.courierApp.waitList.denied.title'));
        $DTO->setBody(__('notification.courierApp.waitList.denied.body', [
            'statusName' => $waitList->refStatus->name,
            'number'     => $waitList->getNumber(),
        ]));
        $DTO->setData($this->getData($waitList));

        $this->repository->send($DTO);
    }

    private function getData(WaitListStatus $waitListStatus): array
    {
        return [
            'type' => $waitListStatus->client_type === Order::class
                ? 'orderTake'
                : 'delivery',
            'id'   => (string)$waitListStatus->client_id,
        ];
    }
}
