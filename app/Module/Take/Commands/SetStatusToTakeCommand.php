<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class SetStatusToTakeCommand implements ShouldQueue
{
    public function __construct(
        public int $takeId,
        public int $statusId
    ) {
    }
}
