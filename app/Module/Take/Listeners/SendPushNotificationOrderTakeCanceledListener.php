<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners;

use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\DTO\PushNotificationDTO;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Events\OrderTakeStatusUpdatedEvent;

final readonly class SendPushNotificationOrderTakeCanceledListener
{
    public function __construct(
        private OrderTakeQuery $query,
        private SendPushNotificationRepository $repository,
    ) {
    }

    public function handle(OrderTakeStatusUpdatedEvent $event): void
    {
        $orderTake = $this->query->getById($event->orderTakeId);

        if (!$orderTake->courier_id) {
            return;
        }

        if (!$orderTake->isStatusCancelled()) {
            return;
        }

        $DTO = new PushNotificationDTO();
        $DTO->setUserId($orderTake->courier->user_id);
        $DTO->setTitle(__('notification.courierApp.orderTake.canceled.title'));
        $DTO->setBody(__('notification.courierApp.orderTake.canceled.body', [
            'invoiceNumber' => $orderTake->invoice->invoice_number,
            'orderNumber'   => $orderTake->order_number,
        ]));
        $DTO->setData([
            'type' => 'orderTake',
            'id'   => (string)$orderTake->order_id,
        ]);

        $this->repository->send($DTO);
    }
}
