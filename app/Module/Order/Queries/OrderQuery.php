<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Order\Contracts\Queries\OrderQuery as OrderQueryContract;
use App\Module\Order\Models\Order;
use App\Module\Take\DTO\OrderTakeShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class OrderQuery implements OrderQueryContract
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Order
    {
        /** @var Order|null */
        return Order::query()
            ->select($columns)
            ->with($relations)
            ->find($id);
    }

    public function getWithTakesAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator
    {
        return Order::query()
            ->select(['id', 'number', 'sender_id', 'company_id'])
            ->with([
                'take:take_date,status_id,wait_list_status_id,customer_id,city_id,courier_id,order_id,invoice_id',
                'take.status:id,title',
                'take.waitListStatus:id,name',
                'take.customer:id,full_name,address,phone,additional_phone,sector_id',
                'take.customer.sector:id,name,dispatcher_sector_id',
                'take.city:id,name,region_id,type_id,code',
                'take.courier:id,full_name,phone_number,iin',
                'take.invoice:id,period_id',
                'take.invoice.period:id,from,to',
                'orderTakes:id,weight,volume,places,status_id,invoice_id,order_id',
                'orderTakes.invoice:id,invoice_number',
                'company:id,short_name',
                'lastWaitListMessage:id,number,comment',
                'sender:id,latitude,longitude',
                'take.takenStatus:id,code,order_id,created_at',
            ])
            ->when($DTO->companyId, fn($q) => $q->where('company_id', $DTO->companyId))
            ->when($DTO->orderNumber, fn($q) => $q->where('number', 'like', '%' . $DTO->orderNumber . '%'))
            ->whereHas('take')
            ->whereHas('orderTakes', function ($query) use ($DTO) {
                $query
                    ->when($DTO->dateFrom, fn($q) => $q->whereDate('take_date', '>=', $DTO->dateFrom))
                    ->when($DTO->dateTo, fn($q) => $q->whereDate('take_date', '<=', $DTO->dateTo))
                    ->when($DTO->courierId, fn($q) => $q->where('courier_id', $DTO->courierId))
                    ->when($DTO->cityId, fn($q) => $q->where('city_id', $DTO->cityId))
                    ->when($DTO->statusIds, fn($q) => $q->whereIn('status_id', $DTO->statusIds))
                    ->when($DTO->notInStatusIds, fn($q) => $q->whereNotIn('status_id', $DTO->notInStatusIds))
                    ->when($DTO->dispatcherSectorId, fn($q) => $q->whereHas('customer', function ($cq) use ($DTO) {
                        $cq->where('dispatcher_sector_id', $DTO->dispatcherSectorId);
                    }))
                    ->when($DTO->waitListStatusIds, fn($q) => $q->whereIn('wait_list_status_id', $DTO->waitListStatusIds))
                    ->when($DTO->createdAtFrom, fn($q) => $q->whereDate('created_at', '>=', $DTO->createdAtFrom))
                    ->when($DTO->createdAtTo, fn($q) => $q->whereDate('created_at', '<=', $DTO->createdAtTo));
            })
            ->when($DTO->address, fn(Builder $q) => $q->whereRelation('orderTakes.customer', 'address', 'like', '%' . $DTO->address . '%'))
            ->when($DTO->periodId, fn(Builder $q) => $q->whereRelation('orderTakes.invoice', 'period_id', $DTO->periodId))
            ->whereNull('parent_id')
            ->withCount('invoices')
            ->orderByRaw('(SELECT status_id FROM order_takes WHERE order_takes.order_id = orders.id ORDER BY id desc limit 1)')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getWithTakes(int $orderId): Order
    {
        /** @var Order */
        return Order::query()
            ->select(['id', 'number', 'sender_id', 'company_id'])
            ->with([
                'sender:id,full_name,full_address,phone,additional_phone,warehouse_id,latitude,longitude,comment,city_id,sector_id',
                'sender.city:id,name',
                'sender.sector:id,name',
                'take:id,take_date,status_id,wait_list_status_id,courier_id,order_id,invoice_id',
                'take.waitListStatus:id,name',
                'take.status:id,title',
                'take.courier:id,full_name,phone_number,iin',
                'orderTakes:id,weight,volume,places,status_id,invoice_id,order_id',
                'orderTakes.invoice.receiver:id,city_id',
                'orderTakes.invoice.receiver.city:id,name',
                'orderTakes.status:id,title',
                'orderTakes.cargo:id,invoice_id,size_type',
                'company:id,short_name,name,manager_id',
                'company.manager:id,email',
                'invoices:id,order_id,type,period_id,take_date,take_time,receiver_id',
                'waitListStatuses' => fn (MorphMany $q): MorphMany => $q->orderByDesc('id')
                    ->with(['user', 'refStatus', 'client']),
            ])
            ->where('id', $orderId)
            ->firstOrFail();
    }
}
