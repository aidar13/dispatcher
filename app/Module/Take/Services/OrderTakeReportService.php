<?php

declare(strict_types=1);

namespace App\Module\Take\Services;

use App\Module\Take\Contracts\Queries\OrderTakeQuery;
use App\Module\Take\Contracts\Services\OrderTakeReportService as OrderTakeReportServiceContract;
use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Support\Collection;

final readonly class OrderTakeReportService implements OrderTakeReportServiceContract
{
    public function __construct(
        private OrderTakeQuery $query
    ) {
    }

    public function getForExcel(OrderTakeShowDTO $DTO, array $columns = ['*'], array $relations = []): Collection
    {
        $orders = $this->query->getAllForExport(
            $DTO,
            ['*'],
            [
                'statuses:id,invoice_id,order_id,title,code,created_at', 'takenStatus:id,title,code,created_at,invoice_id,order_id',
                'waitListStatus:id,name', 'customer:id,address,sector_id', 'customer:id,name',
                'customer.sector:id,name', 'city:id,name', 'courier:id,full_name', 'company:id,name',
                'invoice:id,period_id,order_id', 'invoice.period:id,title', 'order:id,number,sender_id',
                'order.sender:id,full_name,warehouse_id', 'status:id,title', 'shipmentType:id,title'
            ]
        );

        if ($orders->isEmpty()) {
            return collect();
        }

        return $orders;
    }
}
