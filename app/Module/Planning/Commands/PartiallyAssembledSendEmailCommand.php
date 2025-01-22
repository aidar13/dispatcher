<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use Illuminate\Support\Collection;

final class PartiallyAssembledSendEmailCommand
{
    public function __construct(
        public readonly Collection $partiallyAssembledInvoicesCollection,
        public readonly int $containerId,
    ) {
    }
}
