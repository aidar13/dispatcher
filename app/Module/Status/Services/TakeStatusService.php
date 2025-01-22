<?php

declare(strict_types=1);

namespace App\Module\Status\Services;

use App\Helpers\DateHelper;
use App\Module\Status\Contracts\Queries\OrderStatusQuery;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Contracts\Services\TakeStatusService as TakeStatusServiceContract;
use App\Module\Status\DTO\TakeStatusDTO;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

final class TakeStatusService implements TakeStatusServiceContract
{
    public function getCurrentStatusKey(OrderTake $take): string
    {
        $orderStatuses = $take->statuses ?? null;

        if (!$orderStatuses) {
            return StatusType::TAKE_STATUS_NOT_ASSIGNED;
        }

        $status = StatusType::TAKE_STATUS_NOT_ASSIGNED;

        if ($this->getByStatusCode($orderStatuses, RefStatus::CODE_ASSIGNED_TO_COURIER)) {
            $status = StatusType::TAKE_STATUS_ASSIGNED_TO_COURIER;
        }

        if ($this->getByStatusCode($orderStatuses, RefStatus::CODE_CARGO_PICKED_UP)) {
            $status = StatusType::TAKE_STATUS_TAKEN;
        }

        if ($this->getByStatusCode($orderStatuses, RefStatus::CODE_CARGO_HANDLING)) {
            $status = StatusType::TAKE_STATUS_CARGO_HANDLING;
        }

        return $status;
    }

    /**
     * @psalm-suppress InvalidArgument
     * @psalm-suppress InvalidPropertyAssignment
     */
    public function getStatusHistory(OrderTake $take): Collection
    {
        $orderStatuses = $take->statuses;
        $res           = collect();

        $res->push(new TakeStatusDTO(StatusType::TAKE_STATUS_NOT_ASSIGNED, $this->getByStatusCode($orderStatuses, RefStatus::CODE_CREATE)->created_at ?? null));
        $res->push(new TakeStatusDTO(StatusType::TAKE_STATUS_ASSIGNED_TO_COURIER, $this->getByStatusCode($orderStatuses, RefStatus::CODE_ASSIGNED_TO_COURIER)->created_at ?? null));
        $res->push(new TakeStatusDTO(StatusType::TAKE_STATUS_TAKEN, $this->getByStatusCode($orderStatuses, RefStatus::CODE_CARGO_PICKED_UP)->created_at ?? null));
        $res->push(new TakeStatusDTO(StatusType::TAKE_STATUS_CARGO_HANDLING, $this->getByStatusCode($orderStatuses, RefStatus::CODE_CARGO_HANDLING)->created_at ?? null));

        $state         = TakeStatusDTO::STATE_WAITING;
        $currentStatus = $this->getCurrentStatusKey($take);

        $res->reverse()->map(function (TakeStatusDTO $takeStatusDTO) use (&$state, $currentStatus) {
            if ($takeStatusDTO->statusName === $currentStatus) {
                $takeStatusDTO->state = TakeStatusDTO::STATE_CURRENT;
                $state                = TakeStatusDTO::STATE_DONE;
                return;
            }

            $takeStatusDTO->state = $state;
        });

        return $res->map(function (TakeStatusDTO $takeStatusDTO) {
            return $takeStatusDTO->toArray();
        });
    }

    private function getByStatusCode(Collection $orderStatuses, int $statusCode): ?OrderStatus
    {
        return $orderStatuses->sortBy('created_at')->first(function (OrderStatus $orderStatus) use ($statusCode) {
            return $orderStatus->equalsCode($statusCode);
        });
    }

    public function getTakeStatusByInvoiceIdAndDate(int $invoiceId, ?Carbon $date = null): int
    {
        $date = $date
            ? DateHelper::getDate($date)
            : DateHelper::getDate(now());

        /** @var OrderStatusQuery $query */
        $query = app(OrderStatusQuery::class);

        $status = $query->getLastTakeStatusInvoiceIdAndDate(
            $invoiceId,
            $date
        );

        return match ($status?->code) {
            RefStatus::CODE_CARGO_HANDLING       => StatusType::ID_CARGO_HANDLING,
            RefStatus::CODE_CARGO_AWAIT_SHIPMENT => StatusType::ID_CARGO_HANDLING,
            RefStatus::CODE_CARGO_PICKED_UP      => StatusType::ID_TAKEN,
            RefStatus::CODE_ASSIGNED_TO_COURIER  => StatusType::ID_ASSIGNED,
            default                              => StatusType::ID_NOT_ASSIGNED
        };
    }
}
