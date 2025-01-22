<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Events\OrderTake;

final readonly class CourierShortcomingFilesSavedEvent
{
    public function __construct(
        public int $orderId,
    ) {
    }
}
