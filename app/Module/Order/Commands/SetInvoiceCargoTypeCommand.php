<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class SetInvoiceCargoTypeCommand implements ShouldQueue
{
    public function __construct(public int $invoiceId)
    {
    }
}
