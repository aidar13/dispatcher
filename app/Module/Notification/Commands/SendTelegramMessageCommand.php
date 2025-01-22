<?php

declare(strict_types=1);

namespace App\Module\Notification\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

readonly class SendTelegramMessageCommand implements ShouldQueue
{
    public function __construct(
        public int $chatId,
        public string $message
    ) {
    }
}
