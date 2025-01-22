<?php

declare(strict_types=1);

namespace App\Module\Notification\DTO;

final class WebNotificationDTO
{
    public ?int $objectId;
    public int $typeId;
    public array $userIds;
    public ?array $data;

    public function setObjectId(int $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function setTypeId(int $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function setUserIds(array $userIds): void
    {
        $this->userIds = $userIds;
    }

    public function setData(?array $data): void
    {
        $this->data = $data;
    }
}
