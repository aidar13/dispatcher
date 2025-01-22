<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class SetWaitListStatusCommand implements ShouldQueue
{
    public function __construct(
        public int $orderId,
        public int $code,
        public int $userId,
    ) {
    }
}
