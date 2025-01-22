<?php

declare(strict_types=1);

namespace App\Module\Delivery\Listeners;

use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Events\DeliveryStatusUpdatedEvent;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\DTO\PushNotificationDTO;

final readonly class SendPushNotificationDeliveryCanceledListener
{
    public function __construct(
        public DeliveryQuery $query,
        public SendPushNotificationRepository $repository,
    ) {
    }

    public function handle(DeliveryStatusUpdatedEvent $event): void
    {
        $delivery = $this->query->getById($event->deliveryId);

        if (!$delivery->courier_id) {
            return;
        }

        if (!$delivery->isCanceled()) {
            return;
        }

        $DTO = new PushNotificationDTO();
        $DTO->setUserId($delivery->courier->user_id);
        $DTO->setTitle(__('notification.courierApp.delivery.canceled.title'));
        $DTO->setBody(__('notification.courierApp.delivery.canceled.body', [
            'invoiceNumber' => $delivery->invoice_number,
        ]));
        $DTO->setData([
            'type' => 'delivery',
            'id'   => (string)$delivery->id,
        ]);

        $this->repository->send($DTO);
    }
}
