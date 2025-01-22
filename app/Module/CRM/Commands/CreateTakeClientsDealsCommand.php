<?php

declare(strict_types=1);

namespace App\Module\CRM\Commands;

final class CreateTakeClientsDealsCommand
{
    public function __construct(
        public readonly int $orderId,
        public readonly int $statusCode,
        public readonly int $userId
    ) {
    }
}
