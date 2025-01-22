<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\CourierApp\Events\Delivery\DeliveryInfoWaitListStatusChangedEvent;
use Illuminate\Bus\Dispatcher;

final class ChangeDeliveryWaitListStatusOneCListener
{
    public function __construct(
        private readonly Dispatcher $dispatcher
    ) {
    }

    public function handle(DeliveryInfoWaitListStatusChangedEvent $event): void
    {
        $this->dispatcher->dispatch(new ChangeDeliveryStatusInOneCCommand($event->id));
    }
}
