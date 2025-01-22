<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateFastDeliveryOrderByContainerCommand implements ShouldQueue
{
    public string $queue = 'fast_delivery_orders';

    public function __construct(
        public readonly int $containerId
    ) {
    }
}
