<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Events\SenderCreatedEvent;
use App\Module\Order\Events\SenderUpdatedEvent;
use App\Module\Take\Commands\UpdateCustomerSectorWithSenderIdCommand;

final class SetTakeCustomerSectorListener
{
    public function handle(SenderCreatedEvent|SenderUpdatedEvent $event): void
    {
        dispatch(new UpdateCustomerSectorWithSenderIdCommand($event->id));
    }
}
