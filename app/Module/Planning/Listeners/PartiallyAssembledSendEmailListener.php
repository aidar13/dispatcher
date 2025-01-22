<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Planning\Commands\PartiallyAssembledSendEmailCommand;
use App\Module\Planning\Events\PartiallyAssembledInvoicesNotificationEvent;

final class PartiallyAssembledSendEmailListener
{
    public function handle(PartiallyAssembledInvoicesNotificationEvent $event): void
    {
        dispatch(new PartiallyAssembledSendEmailCommand(
            $event->partiallyAssembledInvoicesCollection,
            $event->containerId
        ));
    }
}
