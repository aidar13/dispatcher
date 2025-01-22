<?php

declare(strict_types=1);

namespace App\Module\Status\Listeners;

use App\Module\Order\Commands\SetInvoiceWaitListIdCommand;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use Illuminate\Support\Arr;

final class SetInvoiceWaitListIdListener
{
    public function handle(OrderStatusCreatedEvent $event): void
    {
        if (!$waitListId = Arr::get(RefStatus::WAIT_LIST_STATUSES, $event->code)) {
            return;
        }

        dispatch(new SetInvoiceWaitListIdCommand($event->invoiceId, $waitListId));
    }
}
