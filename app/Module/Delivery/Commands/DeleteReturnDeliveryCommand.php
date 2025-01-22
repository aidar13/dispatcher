<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class DeleteReturnDeliveryCommand implements ShouldQueue
{
    public function __construct(public readonly int $invoiceId)
    {
    }
}
