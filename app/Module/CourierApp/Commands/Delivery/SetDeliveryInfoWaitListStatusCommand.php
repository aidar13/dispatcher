<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery;

use App\Module\CourierApp\DTO\WaitListStatus\SetWaitListStatusDTO;

final class SetDeliveryInfoWaitListStatusCommand
{
    public function __construct(
        public readonly int $id,
        public readonly SetWaitListStatusDTO $DTO
    ) {
    }
}
