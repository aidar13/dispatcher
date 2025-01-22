<?php

declare(strict_types=1);

namespace App\Module\User\Events;

final class UserCreatedEvent
{
    public function __construct(
        public readonly int $userId,
    ) {
    }
}
