<?php

declare(strict_types=1);

namespace App\Module\Delivery\Services;

use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Services\DeliveryService as DeliveryServiceContract;
use App\Module\Delivery\DTO\DeliveryReportDTO;
use App\Module\Delivery\DTO\DeliveryShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class DeliveryService implements DeliveryServiceContract
{
    public function __construct(
        private readonly DeliveryQuery $query,
    ) {
    }

    public function getAllPaginated(DeliveryShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }

    public function getForExport(DeliveryReportDTO $DTO): Collection
    {
        return $this->query->getAllCollection(
            $DTO,
            ['*'],
            [
                'invoice:id,order_id,receiver_id,shipment_id,created_at',
                'invoice.order:id', 'invoice.statuses:id,invoice_id,code,comment,created_at',
                'invoice.order.cancelledOrder:id,parent_id', 'company:id,name', 'courier:id,full_name',
                'city:id,name', 'customer:id,sector_id,address', 'customer.sector:id,name', 'invoice.receiver:id,warehouse_id',
                'invoice.shipmentType:id,title', 'status:id,title', 'waitListStatus:id,name',
                'invoice.waitListStatuses' => fn($query) => $query->with(['refStatus', 'child'])
            ]
        );
    }
}
