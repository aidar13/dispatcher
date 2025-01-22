<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use Illuminate\Support\Collection;

final class CreateContainerInvoicesCommand
{
    public function __construct(
        public readonly int $containerId,
        public readonly Collection $invoiceIds
    ) {
    }
}
