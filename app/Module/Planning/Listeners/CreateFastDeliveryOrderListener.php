<?php

declare(strict_types=1);

namespace App\Module\Planning\Listeners;

use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Planning\Events\ContainerStatusUpdatedEvent;

final readonly class CreateFastDeliveryOrderListener
{
    public function handle(ContainerStatusUpdatedEvent $event): void
    {
        dispatch(new CreateFastDeliveryOrderByContainerCommand($event->DTO->containerId));
    }
}
