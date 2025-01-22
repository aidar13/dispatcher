<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\DTO\PushNotificationDTO;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Take\Events\OrderTakesAssignedToCourierEvent;

final readonly class CourierAssignToOrderTakeNotificationListener
{
    public function __construct(
        private OrderQuery $orderQuery,
        private CourierQuery $courierQuery,
        private SendPushNotificationRepository $repository,
    ) {
    }

    public function handle(OrderTakesAssignedToCourierEvent $event): void
    {
        $order   = $this->orderQuery->getById($event->orderId);
        $courier = $this->courierQuery->getById($event->courierId);

        $DTO = new PushNotificationDTO();
        $DTO->setUserId($courier->user_id);
        $DTO->setTitle(__('notification.courierApp.orderTake.assigned.title'));
        $DTO->setBody(__('notification.courierApp.orderTake.assigned.body', [
            'orderNumber' => $order->number,
        ]));
        $DTO->setData([
            'type' => 'orderTake',
            'id'   => (string)$order->id,
        ]);

        $this->repository->send($DTO);
    }
}
