<?php

declare(strict_types=1);

namespace App\Module\Delivery\Listeners;

use App\Module\Delivery\Commands\SetDeliveryWaitListCommand;
use App\Module\Delivery\DTO\SetWaitListStatusDTO;

final class IntegrationSetDeliveryWaitListListener
{
    public function handle($event): void
    {
        dispatch(new SetDeliveryWaitListCommand(SetWaitListStatusDTO::fromEvent($event->DTO)));
    }
}
