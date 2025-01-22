<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateOrderCommand;
use App\Module\Order\Commands\UpdateOrderCommand;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Order\Contracts\Repositories\UpdateOrderRepository;

final class UpdateOrderHandler
{
    public function __construct(
        private readonly OrderQuery $orderQuery,
        private readonly UpdateOrderRepository $updateOrderRepository
    ) {
    }

    public function handle(UpdateOrderCommand $command): void
    {
        $order = $this->orderQuery->getById($command->DTO->id);

        if (!$order) {
            dispatch(new CreateOrderCommand($command->DTO));
            return;
        }

        $order->company_id = $command->DTO->companyId;
        $order->number     = $command->DTO->number;
        $order->sender_id  = $command->DTO->senderId;
        $order->user_id    = $command->DTO->userId;
        $order->source     = $command->DTO->source;
        $order->parent_id  = $command->DTO->parentId;

        $this->updateOrderRepository->update($order);
    }
}
