<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Delivery;

use App\Module\CourierApp\Commands\Delivery\CalculateCarOccupancyCommand;
use App\Module\CourierApp\Commands\Delivery\IntegrationOneC\ChangeDeliveryStatusInOneCCommand;
use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Status\Models\StatusType;

final class DeliveryStatusChangedListener
{
    public function __construct(
        private readonly DeliveryQuery $query,
    ) {
    }

    public function handle(DeliveryStatusChangedEvent $event): void
    {
        $delivery = $this->query->getById($event->id);

        if ($delivery->status_id === StatusType::ID_DELIVERED) {
            dispatch(new ChangeDeliveryStatusInOneCCommand($event->id));
            dispatch(new CalculateCarOccupancyCommand($event->id));
        }
    }
}
