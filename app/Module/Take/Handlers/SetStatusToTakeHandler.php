<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Status\Models\StatusType;
use App\Module\Take\Commands\SetStatusToTakeCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\Events\OrderTakeStatusUpdatedEvent;

final readonly class SetStatusToTakeHandler
{
    public function __construct(
        private OrderTakeQuery $query,
        private UpdateOrderTakeRepository $repository
    ) {
    }

    public function handle(SetStatusToTakeCommand $command): void
    {
        $orderTake = $this->query->getById($command->takeId);

        if ($orderTake->isCompleted() && $command->statusId != StatusType::ID_TAKE_CANCELED) {
            return;
        }

        $orderTake->setTakeStatus($command->statusId);

        $this->repository->update($orderTake);

        event(new OrderTakeStatusUpdatedEvent($orderTake->id));
    }
}
