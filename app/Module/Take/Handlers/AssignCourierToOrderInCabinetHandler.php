<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Gateway\Contracts\Integration\SendToCabinetRepository;
use App\Module\Take\Commands\AssignCourierToOrderInCabinetCommand;

final class AssignCourierToOrderInCabinetHandler
{
    public function __construct(
        private readonly SendToCabinetRepository $repository
    ) {
    }

    public function handle(AssignCourierToOrderInCabinetCommand $command): void
    {
        $this->repository->assignOrderTakes($command->orderIds, $command->courierId, $command->storeOrderStatus);
    }
}
