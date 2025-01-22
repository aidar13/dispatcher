<?php

declare(strict_types=1);

namespace App\Module\User\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class UpdateUserCommand implements ShouldQueue
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
