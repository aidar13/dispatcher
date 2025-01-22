<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Commands\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCCommand;
use App\Module\CourierApp\Events\OrderTake\TakeWaitListStatusChangedEvent;

final class ChangeTakeWaitListStatusOneCListener
{
    public function handle(TakeWaitListStatusChangedEvent $event): void
    {
        dispatch(new ChangeOrderTakeStatusInOneCCommand($event->takeId));
    }
}
