<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class AssignCourierToOrderInCabinetCommand implements ShouldQueue
{
    public function __construct(
        public int $courierId,
        public array $orderIds,
        public bool $storeOrderStatus,
    ) {
    }
}
