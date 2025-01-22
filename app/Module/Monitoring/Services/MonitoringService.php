<?php

declare(strict_types=1);

namespace App\Module\Monitoring\Services;

use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Monitoring\Contracts\Services\MonitoringService as MonitoringServiceContract;
use App\Module\Monitoring\DTO\CourierInfoShowDTO;
use App\Module\Monitoring\DTO\DeliveryInfoShowDTO;
use App\Module\Monitoring\DTO\MonitoringDeliveryDTO;
use App\Module\Monitoring\DTO\MonitoringTakeDTO;
use App\Module\Monitoring\DTO\TakeInfoShowDTO;
use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use Illuminate\Support\Collection;

final class MonitoringService implements MonitoringServiceContract
{
    public function __construct(
        public readonly DeliveryQuery $deliveryQuery,
        public readonly OrderTakeQuery $orderTakeQuery,
        public readonly CourierQuery $courierQuery
    ) {
    }

    public function getDeliverInfo(DeliveryInfoShowDTO $DTO): MonitoringDeliveryDTO
    {
        $deliveries = $this->deliveryQuery->getByDispatcherSectorAndCreatedInterval(
            $DTO->dispatcherSectorId,
            $DTO->createdAtFrom,
            $DTO->createdAtTo,
            ['id', 'customer_id', 'invoice_id', 'status_id'],
            ['customer:id,sector_id,dispatcher_sector_id', 'customer.sector:id,name']
        );

        return MonitoringDeliveryDTO::fromCollection($deliveries);
    }

    public function getTakeInfo(TakeInfoShowDTO $DTO): MonitoringTakeDTO
    {
        $takes = $this->orderTakeQuery->getByDispatcherSectorAndCreatedInterval(
            $DTO,
            ['id', 'customer_id', 'status_id'],
            ['customer:id,sector_id,dispatcher_sector_id', 'customer.sector:id,name']
        );

        return MonitoringTakeDTO::fromCollection($takes);
    }

    public function getCourierInfo(CourierInfoShowDTO $DTO): Collection
    {
        return $this->courierQuery->getTakesAndDeliveriesByDispatcherSectorIdAndCreatedAtInterval($DTO);
    }
}
