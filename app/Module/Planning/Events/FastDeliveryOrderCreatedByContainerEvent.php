<?php

declare(strict_types=1);

namespace App\Module\Planning\Events;

use App\Module\Order\DTO\Integration\FastDeliveryOrderDTO;

final class FastDeliveryOrderCreatedByContainerEvent
{
    public function __construct(
        public readonly int $containerId,
        public readonly FastDeliveryOrderDTO $DTO
    ) {
    }
}
