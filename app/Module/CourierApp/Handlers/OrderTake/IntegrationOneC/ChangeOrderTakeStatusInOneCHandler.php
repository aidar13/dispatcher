<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake\IntegrationOneC;

use App\Module\CourierApp\Commands\OrderTake\IntegrationOneC\ChangeOrderTakeStatusInOneCCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;

final class ChangeOrderTakeStatusInOneCHandler
{
    public function __construct(
        private readonly OrderTakeQuery $query,
        private readonly UpdateOrderTakeRepository $repository,
    ) {
    }

    public function handle(ChangeOrderTakeStatusInOneCCommand $command)
    {
        $orderTake = $this->query->getById($command->id);

        $this->repository->update($orderTake);
    }
}
