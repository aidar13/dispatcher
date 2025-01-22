<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

final class AssignCourierToTakeCargoCommand
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly int $courierId,
        public readonly int $userId
    ) {
    }
}
