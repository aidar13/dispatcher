<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\SetTakeInfoWaitListStatusCommand;
use App\Module\CourierApp\Events\OrderTake\TakeWaitListStatusChangedEvent;
use App\Module\Status\Contracts\Queries\RefStatusQuery;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;

final class SetTakeInfoWaitListStatusHandler
{
    public function __construct(
        private readonly OrderTakeQuery $query,
        private readonly RefStatusQuery $refStatusQuery,
        private readonly UpdateOrderTakeRepository $repository
    ) {
    }

    public function handle(SetTakeInfoWaitListStatusCommand $command): void
    {
        $take   = $this->query->getById($command->takeId);
        $status = $this->refStatusQuery->findByCode($command->DTO->statusCode);

        if (!$status->wait_list_type) {
            throw new \DomainException('Статус не найден!');
        }

        $take->wait_list_status_id = $status->id;

        $this->repository->update($take);

        event(new TakeWaitListStatusChangedEvent($take->id, $command->DTO));
    }
}
