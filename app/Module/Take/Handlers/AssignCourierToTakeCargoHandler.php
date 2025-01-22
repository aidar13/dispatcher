<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Status\Models\StatusType;
use App\Module\Take\Commands\AssignCourierToTakeCargoCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\Events\OrderTakeAssignedToCourierEvent;

final class AssignCourierToTakeCargoHandler
{
    public function __construct(
        private readonly UpdateOrderTakeRepository $repository,
        private readonly OrderTakeQuery $takeQuery
    ) {
    }

    public function handle(AssignCourierToTakeCargoCommand $command): void
    {
        $takeInfo = $this->takeQuery->getByInvoiceId($command->invoiceId);

        $takeInfo->courier_id = $command->courierId;

        if ($takeInfo->isStatusNotAssigned()) {
            $takeInfo->status_id = StatusType::ID_ASSIGNED;
        }

        $this->repository->update($takeInfo);

        event(new OrderTakeAssignedToCourierEvent($takeInfo->id, $command->userId));
    }
}
