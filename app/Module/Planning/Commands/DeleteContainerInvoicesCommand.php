<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\DeleteContainerInvoicesDTO;

final class DeleteContainerInvoicesCommand
{
    public function __construct(
        public readonly DeleteContainerInvoicesDTO $DTO
    ) {
    }
}
