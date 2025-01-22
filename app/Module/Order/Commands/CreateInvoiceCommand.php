<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Order\DTO\InvoiceDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateInvoiceCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public InvoiceDTO $DTO)
    {
    }
}
