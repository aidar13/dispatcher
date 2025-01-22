<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Delivery\Commands\UpdateCustomerSectorWithReceiverIdCommand;
use App\Module\Order\Events\ReceiverUpdatedEvent;

final class SetDeliveryCustomerSectorListener
{
    public function handle(ReceiverUpdatedEvent $event): void
    {
        dispatch(new UpdateCustomerSectorWithReceiverIdCommand($event->id));
    }
}
