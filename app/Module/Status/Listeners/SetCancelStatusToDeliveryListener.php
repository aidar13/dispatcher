<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Delivery\Commands\SetStatusToDeliveryCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;

final class SetCancelStatusToDeliveryListener
{
    public function __construct(private readonly DeliveryQuery $deliveryQuery)
    {
    }

    public function handle(OrderStatusCreatedEvent $event): void
    {
        if (
            !in_array($event->code, RefStatus::DELIVERY_CANCEL_STATUSES)
        ) {
            return;
        }

        $deliveries = $this->deliveryQuery->getAllByInvoiceId($event->invoiceId, ['id', 'invoice_id']);

        foreach ($deliveries as $delivery) {
            dispatch(new SetStatusToDeliveryCommand($delivery->id, StatusType::ID_TAKE_CANCELED));
        }
    }
}
