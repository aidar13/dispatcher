<?php

declare(strict_types=1);

namespace App\Module\Take\Handlers;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Take\Commands\AssignOrderTakesToCourierCommand;
use App\Module\Take\Commands\AssignOrderTakeToCourierCommand;

final class AssignOrderTakesToCourierHandler
{
    public function __construct(
        private readonly CourierQuery $courierQuery
    ) {
    }

    public function handle(AssignOrderTakesToCourierCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->DTO->courierId);

        if (!$courier->isStatusActive()) {
            throw new \DomainException("Курьер не активен, courierId: {$courier->id}");
        }

        foreach ($command->DTO->orderIds as $orderId) {
            dispatch(new AssignOrderTakeToCourierCommand((int)$orderId, $command->DTO->courierId, $command->userId));
        }
    }
}
