<?php

declare(strict_types=1);

namespace App\Module\Status\Events;

final readonly class WaitListStatusCreatedEvent
{
    public function __construct(
        public int $id,
    ) {
    }
}
