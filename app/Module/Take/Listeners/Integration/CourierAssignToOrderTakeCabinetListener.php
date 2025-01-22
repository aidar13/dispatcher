<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners\Integration;

use App\Module\Take\Commands\AssignCourierToOrderInCabinetCommand;
use App\Module\Take\Events\OrderTakesAssignedToCourierEvent;
use Illuminate\Bus\Dispatcher;

final class CourierAssignToOrderTakeCabinetListener
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function handle(OrderTakesAssignedToCourierEvent $event): void
    {
        $this->dispatcher->dispatch(new AssignCourierToOrderInCabinetCommand(
            $event->courierId,
            [$event->orderId],
            false
        ));
    }
}
