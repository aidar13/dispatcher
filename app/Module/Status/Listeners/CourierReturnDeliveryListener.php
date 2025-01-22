<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Delivery\Commands\CreateReturnDeliveryCommand;
use App\Module\Delivery\Commands\DeleteReturnDeliveryCommand;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;

final class CourierReturnDeliveryListener
{
    public function handle(OrderStatusCreatedEvent $event): void
    {
        if ($event->code === RefStatus::CODE_COURIER_RETURN_DELIVERY) {
            dispatch(new CreateReturnDeliveryCommand($event->statusId));
            return;
        }

        if (in_array($event->code, [RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED])) {
            dispatch(new DeleteReturnDeliveryCommand($event->invoiceId));
        }
    }
}
