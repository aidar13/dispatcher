<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class UpdateCustomerSectorWithSenderIdCommand implements ShouldQueue
{
    public function __construct(public int $senderId)
    {
    }
}
