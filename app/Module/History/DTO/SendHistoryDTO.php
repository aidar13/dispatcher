<?php

declare(strict_types=1);

namespace App\Module\History\DTO;

use App\Models\User;

final class SendHistoryDTO
{
    public int $historyTypeId;
    public int $clientId;
    public int $actionId;
    public ?int $userId;
    public ?string $userEmail;
    public ?string $oldValue;
    public ?string $newValue;

    public function setHistoryTypeId(int $historyTypeId): void
    {
        $this->historyTypeId = $historyTypeId;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setActionId(int $actionId): void
    {
        $this->actionId = $actionId;
    }

    public function setOldValue(array $oldValue): void
    {
        $this->oldValue = json_encode($oldValue);
    }

    public function setNewValue(array $newValue): void
    {
        $this->newValue = json_encode($newValue);
    }

    public function setUser(?User $model): void
    {
        $this->userId    = $model?->id;
        $this->userEmail = $model?->email;
    }
}
