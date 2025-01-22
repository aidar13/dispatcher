<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class SetInvoiceSectorsCommand implements ShouldQueue
{
    public string $queue = 'orderSector';

    public function __construct(public int $invoiceId)
    {
    }
}
