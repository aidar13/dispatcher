<?php

declare(strict_types=1);

namespace App\Module\Notification\Contracts\Repositories;

interface SendTelegramMessageRepository
{
    public function send(int $chatId, string $message);
}
