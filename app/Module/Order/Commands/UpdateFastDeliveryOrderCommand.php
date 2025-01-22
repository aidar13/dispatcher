<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\Integration\FastDeliveryOrderDTO;

final class UpdateFastDeliveryOrderCommand
{
    public function __construct(
        public readonly int $containerId,
        public readonly FastDeliveryOrderDTO $DTO
    ) {
    }
}
