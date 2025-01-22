<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class UpdateCustomerSectorWithReceiverIdCommand implements ShouldQueue
{
    public function __construct(public int $senderId)
    {
    }
}
