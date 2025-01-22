<?php

declare(strict_types=1);

namespace App\Module\Notification\Repositories;

use App\Exceptions\CannotSendPushException;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Notification\Contracts\Repositories\SendPushNotificationRepository;
use App\Module\Notification\DTO\PushNotificationDTO;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PushNotificationRepository implements SendPushNotificationRepository
{
    private string $url;

    public function __construct(
        private readonly AuthRepository $repository
    ) {
        $this->url = config('gateway.url');
    }

    public function send(PushNotificationDTO $DTO): void
    {
        $accessToken = $this->repository->auth();
        $path        = '/notification/api/push/send';

        $response = Http::withToken($accessToken)
            ->post($this->url . $path, $DTO->toArray());

        Log::info('Запрос в сервис notification для отправки push уведомлений', [
            'url' => $this->url,
            'DTO' => $DTO->toArray()
        ]);

        if ($response->failed()) {
            throw new CannotSendPushException($response->body());
        }
    }
}
