<?php

declare(strict_types=1);

namespace App\Module\Notification\DTO;

final class PushNotificationDTO
{
    public int $userId;
    public string $title;
    public string $body;
    public ?array $data = [];

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'title'  => $this->title,
            'body'   => $this->body,
            'data'   => $this->data,
        ];
    }
}
