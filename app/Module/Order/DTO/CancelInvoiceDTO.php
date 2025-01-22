<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

final class CancelInvoiceDTO
{
    public int $id;
    public int $sourceId;
    public int $userId;
    public ?string $comment = 'Отмена из сервиса dispatcher';

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}
