<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Order\Commands\UpdateFastDeliveryOrderCommand;
use App\Module\Planning\Commands\UpdateContainerStatusCommand;
use App\Module\Planning\Events\FastDeliveryOrderCreatedByContainerEvent;
use App\Module\Planning\Models\ContainerStatus;

final class SetInternalIdByContainerToFastDeliveryOrderListener
{
    public function handle(FastDeliveryOrderCreatedByContainerEvent $event): void
    {
        dispatch(new UpdateFastDeliveryOrderCommand($event->containerId, $event->DTO));
        dispatch(new UpdateContainerStatusCommand(
            $event->containerId,
            ContainerStatus::ID_ASSEMBLED
        ));
    }
}
