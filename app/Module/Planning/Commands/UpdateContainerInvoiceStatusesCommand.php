<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\ChangeContainerStatusDTO;

final class UpdateContainerInvoiceStatusesCommand
{
    public function __construct(
        public readonly ChangeContainerStatusDTO $DTO
    ) {
    }
}
