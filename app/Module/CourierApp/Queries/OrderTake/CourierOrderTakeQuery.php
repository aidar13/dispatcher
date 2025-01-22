<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Queries\OrderTake;

use App\Module\CourierApp\Contracts\Queries\OrderTake\CourierOrderTakeQuery as CourierOrderTakeQueryContract;
use App\Module\CourierApp\DTO\OrderTake\CourierOrderTakeShowDTO;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Models\OrderTake;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CourierOrderTakeQuery implements CourierOrderTakeQueryContract
{
    /**
     * @param CourierOrderTakeShowDTO $DTO
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(CourierOrderTakeShowDTO $DTO): LengthAwarePaginator
    {
        return OrderTake::query()
            ->select(['order_id', 'shipment_type', 'customer_id', 'company_id', 'courier_id', 'take_date'])
            ->with([
                'order:id,number,sender_id',
                'order.sender:id,city_id',
                'order.sender.city:id,name',
                'shipmentType:id,title',
                'customer:id,full_name,address,phone,additional_phone,sector_id,latitude,longitude',
                'customer.sector:id,name,dispatcher_sector_id',
                'invoice:id,period_id,order_id',
                'order.orderTakes:id,weight,volume,places,order_id,invoice_id,company_id,customer_id,take_date',
                'order.orderTakes.invoice:id,order_id,receiver_id,invoice_number,dop_invoice_number,payment_type,period_id',
                'order.orderTakes.invoice.cargo:id,invoice_id,size_type,product_name',
                'order.orderTakes.invoice.receiver:id,full_name,full_address,city_id',
                'order.orderTakes.invoice.additionalServiceValues:id,type_id',
                'order.orderTakes.customer:id,phone,additional_phone,address,latitude,longitude',
                'order.orderTakes.company:id,short_name,name,bin',
                'order.orderTakes.order.waitListStatuses',
                'company:id,short_name,name',
                'order.invoices:id,order_id,receiver_id',
                'order.invoices.receiver:id,city_id',
                'order.invoices.receiver.city:id,name',
                'order.waitListStatuses',
                'takenStatus',
                'routingItem:id,client_id,client_type,position'
            ])
            ->when($DTO->userId, fn($q) => $q->whereHas('courier', function (Builder $query) use ($DTO) {
                $query->where('user_id', $DTO->userId);
            }))
            ->whereHas('order')
            ->when($DTO->statusIds, fn($q) => $q->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->notInStatusIds, fn($q) => $q->whereNotIn('status_id', $DTO->notInStatusIds))
            ->when($DTO->search, function (Builder $query) use ($DTO) {
                $query->where(function (Builder $q) use ($DTO) {
                    $q->where('order_number', 'like', '%' . $DTO->search . '%')
                        ->orWhereRelation('customer', 'address', 'like', '%' . $DTO->search . '%')
                        ->orWhereRelation('company', 'short_name', 'like', '%' . $DTO->search . '%');
                });
            })
            ->when($DTO->dateFrom && $DTO->dateTo, function ($q) use ($DTO) {
                $q->whereBetween('take_date', [$DTO->dateFrom, $DTO->dateTo]);
            })
            ->when($DTO->takenAtFrom, function (Builder $q) use ($DTO) {
                $q->whereHas('takenStatus', function (Builder $query) use ($DTO) {
                    $query->whereDate('created_at', '>=', $DTO->takenAtFrom);
                });
            })
            ->when($DTO->takenAtTo, function (Builder $q) use ($DTO) {
                $q->whereHas('takenStatus', function (Builder $query) use ($DTO) {
                    $query->whereDate('created_at', '<=', $DTO->takenAtTo);
                });
            })
            ->groupBy('order_id')
            ->orderBy('take_date')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getAllByCourierId(int $courierId): Collection
    {
        /** @var Collection */
        return OrderTake::query()
            ->select(['id', 'order_id', 'invoice_id', 'take_date', 'order_number'])
            ->with(['order', 'invoice'])
            ->where('courier_id', $courierId)
            ->whereHas('order')
            ->where('status_id', StatusType::ID_ASSIGNED)
            ->groupBy('order_id')
            ->get();
    }

    public function getAllByOrderId(int $orderId): Collection|array
    {
        return OrderTake::query()
            ->select(['id', 'places', 'weight', 'invoice_id', 'company_id', 'order_id', 'customer_id', 'take_date', 'volume'])
            ->with([
                'invoice:id,invoice_number,dop_invoice_number,receiver_id,period_id,order_id',
                'invoice.period:id,from,to,title',
                'invoice.receiver:id,city_id,full_name',
                'company:id,bin,name,short_name',
                'customer:id,phone,additional_phone,latitude,longitude',
                'order',
                'order.sender:id,comment,city_id',
                'order.sender.city:id,name',
                'order.waitListStatuses'
            ])
            ->where('order_id', $orderId)
            ->get();
    }
}
