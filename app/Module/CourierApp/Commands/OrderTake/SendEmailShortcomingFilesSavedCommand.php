<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake;

final readonly class SendEmailShortcomingFilesSavedCommand
{
    public function __construct(
        public int $orderId,
    ) {
    }
}
