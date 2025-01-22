<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners\Integration;

use App\Module\Order\Commands\IntegrationChangeTakeDateByOrderCommand;
use App\Module\Take\Events\ChangedTakeDateByOrderEvent;

final readonly class SendTakeDateChangedByOrderToCabinetListener
{
    public function handle(ChangedTakeDateByOrderEvent $event): void
    {
        dispatch(new IntegrationChangeTakeDateByOrderCommand($event->DTO));
//        dispatch(new IntegrationCreateWaitListStatusCommand());
    }
}
