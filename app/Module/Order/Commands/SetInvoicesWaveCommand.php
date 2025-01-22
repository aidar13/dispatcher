<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

final readonly class SetInvoicesWaveCommand
{
    public function __construct(
        public int $waveId,
        public array $invoiceIds
    ) {
    }
}
