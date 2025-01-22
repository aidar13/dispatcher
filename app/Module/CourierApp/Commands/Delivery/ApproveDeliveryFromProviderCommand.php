<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery;

use Illuminate\Contracts\Queue\ShouldQueue;

final class ApproveDeliveryFromProviderCommand implements ShouldQueue
{
    public string $queue = 'deliveryOneC';

    public function __construct(
        public readonly int $invoiceId
    ) {
    }
}
