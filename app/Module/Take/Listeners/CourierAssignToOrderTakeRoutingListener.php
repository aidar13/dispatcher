<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners;

use App\Module\Routing\Commands\CreateCourierRoutingCommand;
use App\Module\Routing\DTO\CreateCourierRoutingDTO;
use App\Module\Take\Events\OrderTakesAssignedToCourierEvent;

final readonly class CourierAssignToOrderTakeRoutingListener
{
    public function handle(OrderTakesAssignedToCourierEvent $event): void
    {
        $dto            = new CreateCourierRoutingDTO();
        $dto->courierId = $event->courierId;

        dispatch(new CreateCourierRoutingCommand($dto));
    }
}
