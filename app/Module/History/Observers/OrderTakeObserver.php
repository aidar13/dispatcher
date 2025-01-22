<?php

declare(strict_types=1);

namespace App\Module\History\Observers;

use App\Module\History\DTO\SendHistoryDTO;
use App\Module\History\Events\Integration\SendHistoryEvent;
use App\Module\History\Models\History;
use App\Module\History\Models\HistoryType;
use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Facades\Auth;

final class OrderTakeObserver
{
    public function created(OrderTake $model): void
    {
        $dto = $this->generateDTO(
            $model,
            History::ACTION_ID_CREATE,
        );

        event(new SendHistoryEvent($dto));
    }

    public function updated(OrderTake $model): void
    {
        $dto = $this->generateDTO(
            $model,
            History::ACTION_ID_UPDATE,
        );

        event(new SendHistoryEvent($dto));
    }

    public function deleted(OrderTake $model): void
    {
        $dto = $this->generateDTO(
            $model,
            History::ACTION_ID_DELETE,
        );

        event(new SendHistoryEvent($dto));
    }

    public function generateDTO(OrderTake $model, int $actionId): SendHistoryDTO
    {
        $dto = new SendHistoryDTO();
        $dto->setActionId($actionId);
        $dto->setClientId($model->id);
        $dto->setHistoryTypeId(HistoryType::ID_ORDER_TAKE);
        $dto->setOldValue($model->getOriginal());
        $dto->setNewValue($model->getAttributes());
        $dto->setUser(Auth::user());

        return $dto;
    }
}
