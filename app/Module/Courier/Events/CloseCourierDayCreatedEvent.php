<?php

declare(strict_types=1);

namespace App\Module\Courier\Events;

final class CloseCourierDayCreatedEvent
{
    public function __construct(
        public readonly int $closeCourierId
    ) {
    }
}
