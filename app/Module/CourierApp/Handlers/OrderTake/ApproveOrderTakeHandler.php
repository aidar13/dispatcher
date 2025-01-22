<?php

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\ApproveOrderTakeCommand;
use App\Module\CourierApp\Events\OrderTake\OrderTakeStatusChangedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;

final readonly class ApproveOrderTakeHandler
{
    public function __construct(
        private UpdateOrderTakeRepository $repository,
        private OrderTakeQuery $query,
    ) {
    }

    public function handle(ApproveOrderTakeCommand $command): void
    {
        $orderTake = $this->query->getById($command->id);

        if ($orderTake->isStatusCancelled()) {
            return;
        }

        $orderTake->places = $command->places;

        if (!$orderTake->isCompleted()) {
            $orderTake->setTakeStatus(StatusType::ID_TAKEN);
        }

        $this->repository->update($orderTake);

//      TODO:: When listener offed, Add here UpdateInvoicePlaceByOrderTakeListener In Event
        event(new OrderTakeStatusChangedEvent(
            $command->id,
            $command->userId,
            RefStatus::CODE_CARGO_PICKED_UP,
            StatusSource::ID_COURIER_APP_V2
        ));
    }
}
