<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Queries\Delivery;

use App\Module\CourierApp\Contracts\Queries\Delivery\CourierDeliveryQuery as CourierDeliveryQueryContract;
use App\Module\CourierApp\DTO\Delivery\CourierDeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Models\AdditionalServiceType;
use App\Module\Status\Models\StatusType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class CourierDeliveryQuery implements CourierDeliveryQueryContract
{
    /**
     * @param CourierDeliveryShowDTO $DTO
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(CourierDeliveryShowDTO $DTO): LengthAwarePaginator
    {
        return Delivery::query()
            ->select([
                'id',
                'invoice_number',
                'courier_id',
                'delivered_at',
                'created_at',
                'company_id',
                'invoice_id',
                'customer_id',
                'status_id',
                'wait_list_status_id',
                'weight',
                'volume_weight',
                'places'
            ])
            ->with([
                'courierStates',
                'invoice:id,receiver_id,invoice_number,dop_invoice_number,shipment_id,order_id,payer_company_id,sla_date,cash_sum,payment_method,should_return_document,payment_type,verify',
                'invoice.payerCompany:id,short_name,name',
                'invoice.order:id,company_id',
                'invoice.cargo:id,invoice_id,annotation,cod_payment,size_type',
                'invoice.receiver:id,full_name,full_address,office,house,comment,phone,additional_phone,latitude,longitude',
                'invoice.additionalServiceValues' => fn($query) => $query->where('type_id', AdditionalServiceType::ID_RISE_TO_THE_FLOOR),
                'customer.sector:id,name,dispatcher_sector_id',
                'customer:id,full_name,address,phone,additional_phone,sector_id,latitude,longitude',
                'company:id,short_name,name,bin',
                'refStatus:id,name',
                'status:id,title',
                'invoice.shipmentType:id,title',
                'invoice.waitListStatuses',
                'routingItem:id,client_id,client_type,position',
                'courierTakes' => fn($query) => $query
                    ->select([
                        'id',
                        'courier_id',
                        'order_id',
                    ])
                    ->with([
                        'order:id,sender_id',
                        'order.sender:id,latitude,longitude',
                    ])
                    ->where('status_id', StatusType::ID_ASSIGNED)
            ])
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->notInStatusIds, fn(Builder $query) => $query->whereNotIn('status_id', $DTO->notInStatusIds))
            ->when($DTO->search, function (Builder $query) use ($DTO) {
                $query->where(function (Builder $q) use ($DTO) {
                    $q->whereRelation('invoice', 'invoice_number', 'like', '%' . $DTO->search . '%')
                        ->orWhereRelation('company', 'short_name', 'like', '%' . $DTO->search . '%')
                        ->orWhereRelation('customer', 'address', 'like', '%' . $DTO->search . '%');
                });
            })
            ->when($DTO->createdAtFrom, fn(Builder $query) => $query->whereDate('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtTo, fn(Builder $query) => $query->whereDate('created_at', '<=', $DTO->createdAtTo))
            ->when($DTO->deliveredAtFrom, fn(Builder $query) => $query->whereDate('delivered_at', '>=', $DTO->deliveredAtFrom))
            ->when($DTO->deliveredAtTo, fn(Builder $query) => $query->whereDate('delivered_at', '<=', $DTO->deliveredAtTo))
            ->when($DTO->deliveryDateFrom, function (Builder $q) use ($DTO) {
                $q->whereHas('invoice', function (Builder $query) use ($DTO) {
                    $query->whereDate('sla_date', '>=', $DTO->deliveryDateFrom);
                });
            })
            ->when($DTO->deliveryDateTo, function (Builder $q) use ($DTO) {
                $q->whereHas('invoice', function (Builder $query) use ($DTO) {
                    $query->whereDate('sla_date', '<=', $DTO->deliveryDateTo);
                });
            })
            ->when($DTO->userId, fn($q) => $q->whereHas('courier', function (Builder $query) use ($DTO) {
                $query->where('user_id', $DTO->userId);
            }))
            ->orderBy('status_id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    /**
     * @param int $id
     * @return Delivery
     */
    public function getById(int $id): Delivery
    {
        /** @var Delivery $delivery */
        $delivery = Delivery::query()
            ->select([
                'id',
                'courier_id',
                'invoice_number',
                'company_id',
                'invoice_id',
                'weight',
                'customer_id',
                'places',
            ])
            ->with([
                'company:id,short_name,bin,name',
                'invoice:id,invoice_number,dop_invoice_number,payment_method,receiver_id,payer_company_id,cash_sum,should_return_document,payment_type,sla_date,order_id,verify',
                'customer:id,latitude,longitude',
                'invoice.receiver:id,full_name,full_address,office,house,comment,phone,additional_phone,latitude,longitude',
                'invoice.order:id,company_id',
                'invoice.payerCompany:id,short_name',
                'invoice.cargo:id,cod_payment,annotation,invoice_id,annotation,size_type',
                'invoice.waitListStatuses',
                'invoice.additionalServiceValues' => fn($query) => $query->where('type_id', AdditionalServiceType::ID_RISE_TO_THE_FLOOR),
                'courierTakes' => fn($query) => $query
                    ->select([
                        'id',
                        'courier_id',
                        'order_id',
                    ])
                    ->with([
                        'order:id,sender_id',
                        'order.sender:id,latitude,longitude',
                    ])
                    ->where('status_id', StatusType::ID_ASSIGNED)
            ])
            ->where('id', $id)
            ->first();

        return $delivery;
    }

    public function getAllByCourierId(int $courierId): Collection
    {
        /** @var Collection */
        return Delivery::query()
            ->select(['id', 'invoice_id', 'invoice_number'])
            ->with(['invoice.order'])
            ->where('courier_id', $courierId)
            ->whereHas('invoice')
            ->where('status_id', StatusType::ID_IN_DELIVERING)
            ->get();
    }
}
