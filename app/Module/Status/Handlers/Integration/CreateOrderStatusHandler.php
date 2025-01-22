<?php

declare(strict_types=1);

namespace App\Module\Status\Handlers\Integration;

use App\Module\Status\Commands\Integration\CreateOrderStatusCommand;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;
use App\Module\Status\Contracts\Repositories\CreateOrderStatusRepository;
use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\OrderStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class CreateOrderStatusHandler
{
    public function __construct(
        private readonly OrderStatusQuery $orderStatusQuery,
        private readonly CreateOrderStatusRepository $createOrderStatusRepository,
    ) {
    }

    public function handle(CreateOrderStatusCommand $command): void
    {
        if ($this->statusExists($command->DTO->id)) {
            return;
        }

        $status                 = new OrderStatus();
        $status->id             = $command->DTO->id;
        $status->invoice_id     = $command->DTO->invoiceId;
        $status->invoice_number = $command->DTO->invoiceNumber;
        $status->order_id       = $command->DTO->orderId;
        $status->code           = $command->DTO->code;
        $status->title          = $command->DTO->title;
        $status->comment        = $command->DTO->comment;
        $status->source_id      = $command->DTO->sourceId;
        $status->user_id        = $command->DTO->userId;
        $status->created_at     = $command->DTO->createdAt;

        $this->createOrderStatusRepository->create($status);

        event(new OrderStatusCreatedEvent(
            $status->invoice_id,
            $status->code,
            $status->id,
            $status->source_id
        ));
    }

    private function statusExists(int $id): bool
    {
        try {
            return (bool)$this->orderStatusQuery->getById($id);
        } catch (ModelNotFoundException) {
            return false;
        }
    }
}
