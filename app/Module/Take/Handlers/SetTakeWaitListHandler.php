<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Take\Commands\SetTakeWaitListCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;

final class SetTakeWaitListHandler
{
    public function __construct(
        private readonly OrderTakeQuery $query,
        private readonly UpdateOrderTakeRepository $repository
    ) {
    }

    public function handle(SetTakeWaitListCommand $command): void
    {
        $orderTake = $this->query->getByInternalId($command->DTO->internalId);

        $orderTake->setWaitListStatus($command->DTO->statusId);

        $this->repository->update($orderTake);
    }
}
