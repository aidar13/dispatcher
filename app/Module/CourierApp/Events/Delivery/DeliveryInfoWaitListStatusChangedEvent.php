<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\Delivery;

use App\Module\CourierApp\DTO\WaitListStatus\SetWaitListStatusDTO;

final class DeliveryInfoWaitListStatusChangedEvent
{
    public function __construct(
        public readonly int $id,
        public readonly SetWaitListStatusDTO $DTO
    ) {
    }
}
