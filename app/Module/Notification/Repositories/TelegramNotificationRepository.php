<?php

declare(strict_types=1);

namespace App\Module\Notification\Repositories;

use App\Exceptions\CannotSendEmailException;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Notification\Contracts\Repositories\SendTelegramMessageRepository;
use Illuminate\Support\Facades\Http;

final class TelegramNotificationRepository implements SendTelegramMessageRepository
{
    private string $url;

    public function __construct(private readonly AuthRepository $repository)
    {
        $this->url = config('gateway.url');
    }

    public function send(int $chatId, string $message): void
    {
        $accessToken = $this->repository->auth();
        $path        = '/notification/api/telegram/message/send';

        $response = Http::withToken($accessToken)->post($this->url . $path, [
            'chatId'  => $chatId,
            'content' => $message,
        ]);

        if ($response->failed()) {
            throw new CannotSendEmailException($response->body());
        }
    }
}
