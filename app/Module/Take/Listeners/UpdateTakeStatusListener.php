<?php

declare(strict_types=1);

namespace App\Module\Take\Listeners;

use App\Module\Status\Events\OrderStatusCreatedEvent;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Commands\SetStatusToTakeCommand;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Models\OrderTake;

final readonly class UpdateTakeStatusListener
{
    public function __construct(private OrderTakeQuery $query)
    {
    }

    public function handle(OrderStatusCreatedEvent $event): void
    {
        if (
            !in_array(
                $event->code,
                [
                    RefStatus::CODE_CARGO_HANDLING,
                    RefStatus::CODE_CARGO_AWAIT_SHIPMENT,
                    RefStatus::CODE_DELIVERED,
                    RefStatus::CODE_CARGO_PICKED_UP,
                    RefStatus::CODE_ASSIGNED_TO_COURIER,
                    RefStatus::CODE_PICKUP_CANCELED,
                    RefStatus::CODE_ORDER_CANCELLED,
                ]
            )
        ) {
            return;
        }

        $takes = $this->query->getAllByInvoiceId($event->invoiceId);

        foreach ($takes as $take) {
            $statusToSet = $this->getStatus($take, $event);

            if (!$statusToSet) {
                return;
            }

            dispatch(new SetStatusToTakeCommand($take->id, $statusToSet));
        }
    }

    private function getStatus(OrderTake $take, OrderStatusCreatedEvent $event): ?int
    {
        if (
            in_array($event->code, [RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_CARGO_AWAIT_SHIPMENT, RefStatus::CODE_DELIVERED]) &&
            !$take->isCompleted()
        ) {
            return StatusType::ID_CARGO_HANDLING;
        }

        if (
            $event->code === RefStatus::CODE_CARGO_PICKED_UP &&
            ($take->isStatusNotAssigned() ||
                $take->isStatusAssigned())
        ) {
            return StatusType::ID_TAKEN;
        }

        if (
            $event->code === RefStatus::CODE_ASSIGNED_TO_COURIER &&
            $take->isStatusNotAssigned()
        ) {
            return StatusType::ID_ASSIGNED;
        }

        if (
            in_array($event->code, RefStatus::TAKE_CANCEL_STATUSES) &&
            !$take->isCompleted()
        ) {
            return StatusType::ID_TAKE_CANCELED;
        }

        return null;
    }
}
