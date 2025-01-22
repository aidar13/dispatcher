<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

use App\Module\CourierApp\DTO\OrderTake\MassApproveOrderTakeDTO;

final class MassApproveTakesByInvoiceNumbersCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly MassApproveOrderTakeDTO $DTO,
    ) {
    }
}
