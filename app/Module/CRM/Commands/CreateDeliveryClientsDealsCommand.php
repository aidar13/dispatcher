<?php

declare(strict_types=1);

namespace App\Module\CRM\Commands;

final class CreateDeliveryClientsDealsCommand
{
    public function __construct(
        public readonly int $deliveryId,
        public readonly int $statusCode,
        public readonly int $userId
    ) {
    }
}
