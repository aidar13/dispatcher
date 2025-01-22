<?php

declare(strict_types=1);

namespace App\Module\Take\Queries;

use App\Module\Monitoring\DTO\TakeInfoShowDTO;
use App\Module\Status\Models\CommentTemplate;
use App\Module\Status\Models\StatusType;
use App\Module\Take\Contracts\Queries\OrderTakeQuery as OrderTakeQueryContract;
use App\Module\Take\DTO\OrderTakeShowDTO;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class OrderTakeQuery implements OrderTakeQueryContract
{
    public function getAllPaginated(OrderTakeShowDTO $DTO): LengthAwarePaginator
    {
        return $this->initQuery($DTO)
            ->select([
                'id', 'take_date', 'status_id',
                'wait_list_status_id', 'customer_id',
                'city_id', 'courier_id', 'order_id',
                'invoice_id', 'company_id', 'order_number',
            ])
            ->with([
                'order:id,number,sender_id,company_id',
                'status:id,title',
                'waitListStatus:id,name',
                'customer:id,full_name,address,phone,additional_phone,sector_id,latitude,longitude',
                'customer.sector:id,name,dispatcher_sector_id',
                'city:id,name,region_id,type_id,code',
                'courier:id,full_name,phone_number,iin',
                'invoice:id,period_id,order_id',
                'invoice.period:id,from,to,title',
                'order.orderTakes:id,weight,volume,places,status_id,invoice_id,order_id',
                'order.orderTakes.invoice:id,invoice_number',
                'order.orderTakes.status:id,title',
                'order.orderTakes.cargo:id,invoice_id,size_type',
                'company:id,short_name',
                'order.lastWaitListMessage:id,number,comment',
                'order.invoices:id,order_id',
                'dopInvoice.additionalServiceValues:id',
                'takenStatus:id,code,order_id,created_at',
                'order.files',
                'order.waitListStatuses' => fn(MorphMany $q): MorphMany => $q->orderByDesc('id')
                    ->with(['user', 'refStatus', 'client']),
            ])
            ->orderBy('take_date')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getAllForExport(OrderTakeShowDTO $DTO, array $columns = ['*'], array $relations = []): Collection
    {
        /** @var Collection */
        return $this->initQuery($DTO)
            ->select($columns)
            ->with($relations)
            ->get();
    }

    private function initQuery(OrderTakeShowDTO $DTO): Builder
    {
        return OrderTake::query()
            ->whereRelation('order', 'parent_id', '=')
            ->when(
                $DTO->incompletedAllTime,
                fn($q) => $q->whereIn('status_id', StatusType::ORDER_TAKE_INCOMPLETED_STATUSES),
                fn($q) => $q->when($DTO->dateFrom, fn($q) => $q->whereDate('take_date', '>=', $DTO->dateFrom))
                    ->when($DTO->dateTo, fn($q) => $q->whereDate('take_date', '<=', $DTO->dateTo))
            )
            ->when($DTO->courierId, fn($q) => $q->where('courier_id', $DTO->courierId))
            ->when($DTO->cityId, fn($q) => $q->where('city_id', $DTO->cityId))
            ->when($DTO->statusIds, fn($q) => $q->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->notInStatusIds, fn($q) => $q->whereNotIn('status_id', $DTO->notInStatusIds))
            ->when($DTO->waitListStatusIds, fn($q) => $q->whereHas('order.waitListStatuses.refStatus', function ($q) use ($DTO) {
                $q->whereIn('id', $DTO->waitListStatusIds);
            }))
            ->when($DTO->createdAtFrom, fn($q) => $q->whereDate('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtTo, fn($q) => $q->whereDate('created_at', '<=', $DTO->createdAtTo))
            ->when($DTO->companyId, fn($q) => $q->where('company_id', $DTO->companyId))
            ->when($DTO->address, fn($q) => $q->whereHas('customer', function ($q) use ($DTO) {
                $q->where('address', 'like', '%' . $DTO->address . '%');
            }))
            ->when($DTO->orderNumber, function (Builder $builder) use ($DTO) {
                $builder
                    ->where(function (Builder $builder) use ($DTO) {
                        $builder
                            ->whereRelation('order', 'number', 'like', '%' . $DTO->orderNumber)
                            ->orWhere('order_number', 'like', '%' . $DTO->orderNumber);
                    });
            })
            ->when(
                $DTO->dispatcherSectorId,
                function (Builder $q) use ($DTO) {
                    $q->where(function ($q) use ($DTO) {
                        $q
                            ->whereRelation('customer', 'dispatcher_sector_id', $DTO->dispatcherSectorId)
                            ->orWhereRelation('order.sender', 'dispatcher_sector_id', $DTO->dispatcherSectorId);
                    });
                }
            )
            ->when($DTO->periodId, fn($q) => $q->whereHas('invoice', function ($q) use ($DTO) {
                $q->where('period_id', $DTO->periodId);
            }))
            ->when($DTO->hasPackType, fn($q) => $q->whereHas('cargo', function ($q) {
                $q->whereNotNull('size_type');
            }))
            ->when($DTO->hasPackType === false, fn($q) => $q->whereHas('cargo', function ($q) {
                $q->whereNull('size_type');
            }))
            ->when(
                $DTO->waitListComment && $DTO->waitListComment != 'other',
                fn(Builder $query) => $query->whereRelation('order.waitListStatus', 'comment', $DTO->waitListComment)
            )->when(
                $DTO->waitListComment == 'other',
                function (Builder $query) {
                    $comments = CommentTemplate::query()->where('type_id', CommentTemplate::ORDER_TAKE_TYPE_ID)->get()->pluck('text')->toArray();
                    return $query->whereRelation('order.waitListStatus', function (Builder $query) use ($comments) {
                        $query->whereNotIn('comment', $comments);
                    });
                }
            )
            ->groupBy('order_id');
    }

    public function getByInternalId(int $id): ?OrderTake
    {
        /** @var OrderTake|null */
        return OrderTake::query()->where('internal_id', $id)->first();
    }

    public function getByOrderId(int $orderId): Collection
    {
        /** @var Collection */
        return OrderTake::query()->where('order_id', $orderId)->get();
    }

    public function getByInvoiceId(int $invoiceId): ?OrderTake
    {
        /** @var OrderTake|null */
        return OrderTake::query()->where('invoice_id', $invoiceId)->first();
    }

    public function getAllByInvoiceId(int $invoiceId): Collection
    {
        /** @var Collection */
        return OrderTake::query()->where('invoice_id', $invoiceId)->get();
    }

    public function getByInvoiceNumbers(array $invoiceNumbers, array $columns = ['*'], array $relations = []): Collection
    {
        /** @var Collection */
        return OrderTake::query()
            ->select($columns)
            ->with($relations)
            ->whereHas('invoice', function (Builder $query) use ($invoiceNumbers) {
                $query->whereIn('invoice_number', $invoiceNumbers);
            })->get();
    }

    public function getById(int $id): OrderTake
    {
        /** @var OrderTake */
        return OrderTake::query()->where('id', $id)->firstOrFail();
    }

    public function getByDispatcherSectorAndCreatedInterval(
        TakeInfoShowDTO $DTO,
        array $columns = ['*'],
        array $relations = []
    ): Collection {
        /** @var Collection */
        return OrderTake::query()
            ->select($columns)
            ->when($DTO->createdAtFrom, fn(Builder $q) => $q->whereDate('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtTo, fn(Builder $q) => $q->whereDate('created_at', '<=', $DTO->createdAtTo))
            ->when($DTO->takeDateFrom, fn(Builder $q) => $q->whereDate('take_date', '>=', $DTO->takeDateFrom))
            ->when($DTO->takeDateTo, fn(Builder $q) => $q->whereDate('take_date', '<=', $DTO->takeDateTo))
            ->whereHas('customer', function (Builder $query) use ($DTO) {
                $query->when($DTO->dispatcherSectorId, fn(Builder $q) => $q->where('dispatcher_sector_id', $DTO->dispatcherSectorId));
            })
            ->with($relations)
            ->groupBy('order_id')
            ->get();
    }

    public function getLastHourCourierTakesByFullAddress(int $courierId, string $receiverFullAddress): Collection
    {
        /** @var Collection */
        return OrderTake::query()
            ->select(['id', 'courier_id', 'customer_id', 'invoice_id'])
            ->with([
                'takenStatus:id,created_at,invoice_id',
                'customer:id,address'
            ])
            ->where('courier_id', $courierId)
            ->whereHas('customer', function (Builder $query) use ($receiverFullAddress) {
                $query->where('address', $receiverFullAddress);
            })
            ->whereHas('takenStatus', function (Builder $query) {
                $query->where('created_at', '>', Carbon::now()->subHours()->toDateTimeString());
            })
            ->get();
    }
}
