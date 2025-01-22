<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\CreateOrderCommand;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Order\Contracts\Repositories\CreateOrderRepository;
use App\Module\Order\Models\Order;

final class CreateOrderHandler
{
    public function __construct(
        private readonly OrderQuery $orderQuery,
        private readonly CreateOrderRepository $createOrderRepository
    ) {
    }

    public function handle(CreateOrderCommand $command): void
    {
        if ($this->hasOrder($command->DTO->id)) {
            return;
        }

        $order             = new Order();
        $order->id         = $command->DTO->id;
        $order->number     = $command->DTO->number;
        $order->company_id = $command->DTO->companyId;
        $order->sender_id  = $command->DTO->senderId;
        $order->user_id    = $command->DTO->userId;
        $order->source     = $command->DTO->source;
        $order->parent_id  = $command->DTO->parentId;
        $order->created_at = $command->DTO->createdAt;

        $this->createOrderRepository->create($order);
    }

    private function hasOrder(?int $id): bool
    {
        return (bool)$this->orderQuery->getById($id);
    }
}
