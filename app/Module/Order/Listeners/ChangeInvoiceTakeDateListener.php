<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\Order\Commands\ChangeInvoiceTakeDateCommand;
use App\Module\Take\Events\OrderTakeDateChangedEvent;

final class ChangeInvoiceTakeDateListener
{
    public function handle(OrderTakeDateChangedEvent $event): void
    {
        dispatch(new ChangeInvoiceTakeDateCommand($event->invoiceId, $event->takeDate, $event->periodId));
    }
}
