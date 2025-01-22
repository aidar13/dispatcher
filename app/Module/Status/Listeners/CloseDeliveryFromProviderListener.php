<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryFromProviderCommand;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;

final readonly class CloseDeliveryFromProviderListener
{
    public function handle(OrderStatusCreatedEvent $event): void
    {
        if (
            $event->sourceId !== StatusSource::ID_YANDEX ||
            $event->code !== RefStatus::CODE_DELIVERED
        ) {
            return;
        }

        dispatch(new ApproveDeliveryFromProviderCommand($event->invoiceId));
    }
}
