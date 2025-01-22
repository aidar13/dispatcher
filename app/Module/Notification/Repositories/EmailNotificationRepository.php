<?php

declare(strict_types=1);

namespace App\Module\Notification\Repositories;

use App\Exceptions\CannotSendEmailException;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Notification\DTO\EmailNotificationDTO;
use Illuminate\Support\Facades\Http;

final class EmailNotificationRepository implements SendEmailNotificationRepository
{
    private string $url;

    public function __construct(private readonly AuthRepository $repository)
    {
        $this->url = config('gateway.url');
    }

    /**
     * @param EmailNotificationDTO $DTO
     * @return void
     * @throws CannotSendEmailException
     */
    public function send(EmailNotificationDTO $DTO): void
    {
        $accessToken = $this->repository->auth();
        $path        = '/notification/api/email';

        $response = Http::withToken($accessToken)->post($this->url . $path, [
            'emails'      => $DTO->emails,
            'subject'     => $DTO->subject,
            'content'     => $DTO->content,
            'attachments' => $DTO->attachments
        ]);

        if ($response->failed()) {
            throw new CannotSendEmailException($response->body());
        }
    }
}
