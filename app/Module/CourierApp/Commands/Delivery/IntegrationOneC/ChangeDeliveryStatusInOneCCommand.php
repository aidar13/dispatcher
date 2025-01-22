<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery\IntegrationOneC;

use Illuminate\Contracts\Queue\ShouldQueue;

final class ChangeDeliveryStatusInOneCCommand implements ShouldQueue
{
    public string $queue = 'deliveryOneC';

    public function __construct(
        public readonly int $id
    ) {
    }
}
