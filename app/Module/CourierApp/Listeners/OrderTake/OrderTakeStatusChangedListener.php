<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCCommand;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;

final class OrderTakeStatusChangedListener
{
    public function handle(OrderTakeStatusChangedEvent $event): void
    {
        dispatch(new ChangeOrderTakeStatusInOneCCommand($event->orderTakeId));
    }
}
