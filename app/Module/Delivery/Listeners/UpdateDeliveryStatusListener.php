<?php

declare(strict_types=1);

namespace App\Module\Delivery\Listeners;

use App\Module\Delivery\Commands\SetStatusToDeliveryCommand;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;

final class UpdateDeliveryStatusListener
{
    public function __construct(
        private readonly DeliveryQuery $query
    ) {
    }

    public function handle(OrderStatusCreatedEvent $event): void
    {
        if ($event->code !== RefStatus::CODE_DELIVERED) {
            return;
        }

        $delivery = $this->query->getByInvoiceId($event->invoiceId, 'desc');

        if (!$delivery || $delivery->isDelivered()) {
            return;
        }

        dispatch(new SetStatusToDeliveryCommand(
            $delivery->id,
            StatusType::ID_DELIVERED
        ));
    }
}
