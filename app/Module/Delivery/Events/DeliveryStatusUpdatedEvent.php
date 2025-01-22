<?php

declare(strict_types=1);

namespace App\Module\Delivery\Events;

final readonly class DeliveryStatusUpdatedEvent
{
    public function __construct(
        public int $deliveryId
    ) {
    }
}
