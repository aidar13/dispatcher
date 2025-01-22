<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Planning\Commands\PartiallyAssembledSendNotificationCommand;
use App\Module\Planning\Events\PartiallyAssembledInvoicesNotificationEvent;

final class PartiallyAssembledSendNotificationListener
{
    public function handle(PartiallyAssembledInvoicesNotificationEvent $event): void
    {
        dispatch(new PartiallyAssembledSendNotificationCommand(
            $event->partiallyAssembledInvoicesCollection,
            $event->containerId
        ));
    }
}
