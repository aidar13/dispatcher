<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery;

use App\Module\CourierApp\DTO\Delivery\ApproveDeliveryDTO;

final class ApproveDeliveryCommand
{
    public function __construct(
        public readonly int $deliveryId,
        public readonly ApproveDeliveryDTO $DTO,
        public readonly int $userId,
    ) {
    }
}
