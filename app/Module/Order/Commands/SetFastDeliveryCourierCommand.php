<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\SetFastDeliveryCourierDTO;

final class SetFastDeliveryCourierCommand
{
    public function __construct(
        public readonly int $internalId,
        public readonly SetFastDeliveryCourierDTO $DTO
    ) {
    }
}
