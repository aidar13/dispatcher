<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\OrderTake;

use App\Module\CourierApp\DTO\WaitListStatus\SetWaitListStatusDTO;

final class OrderTakeInfoWaitListStatusChangedInfoEvent
{
    public function __construct(
        public readonly int $orderId,
        public readonly SetWaitListStatusDTO $DTO
    ) {
    }
}
