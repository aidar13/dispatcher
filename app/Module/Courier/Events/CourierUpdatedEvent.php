<?php

declare(strict_types=1);

namespace App\Module\Courier\Events;

final readonly class CourierUpdatedEvent
{
    public function __construct(public int $id)
    {
    }
}
