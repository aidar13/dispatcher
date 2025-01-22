<?php

declare(strict_types=1);

namespace App\Module\Order\Events;

final class ReceiverUpdatedEvent
{
    public function __construct(public readonly int $id)
    {
    }
}
