<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Delivery;

use App\Module\Courier\Commands\CreateCourierStopCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Models\Delivery;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;

final class CreateDeliveryCourierStopListener
{
    public function __construct(
        private readonly CourierQuery $query,
        private readonly DeliveryQuery $deliveryQuery,
        private readonly OrderTakeQuery $orderTakeQuery,
    ) {
    }

    public function handle(DeliveryStatusChangedEvent $event): void
    {
        $delivery = $this->deliveryQuery->getById($event->id);

        if ($delivery->invoice->receiver->isPickup()) {
            return;
        }

        $takeInfosSubHour = $this->orderTakeQuery
            ->getLastHourCourierTakesByFullAddress(
                $delivery->courier_id,
                $delivery->invoice->receiver->full_address,
            );

        if (count($takeInfosSubHour)) {
            return;
        }

        $courier = $this->query->getByUserId($event->userId);

        dispatch(new CreateCourierStopCommand(
            $event->id,
            Delivery::class,
            $courier->id,
        ));
    }
}
