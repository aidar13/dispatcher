<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

final class CreateFastDeliveryOrderCommand
{
    public function __construct(
        public readonly int $containerId,
        public readonly ?int $internalOrderId = null,
        public readonly ?int $providerId = null
    ) {
    }
}
