<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Delivery\Commands\SetStatusToDeliveryByInvoiceIdCommand;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;

final class SetDeliveredStatusToDeliveryListener
{
    public function handle(OrderStatusCreatedEvent $event): void
    {
        if ($event->code !== RefStatus::CODE_DELIVERED) {
            return;
        }

        dispatch(new SetStatusToDeliveryByInvoiceIdCommand($event->invoiceId, StatusType::ID_DELIVERED));
    }
}
