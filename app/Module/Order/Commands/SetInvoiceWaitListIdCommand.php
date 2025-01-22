<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class SetInvoiceWaitListIdCommand implements ShouldQueue
{
    public function __construct(
        public readonly int $invoiceId,
        public readonly int $waitListId
    ) {
    }
}
