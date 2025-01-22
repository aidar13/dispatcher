<?php

declare(strict_types=1);

namespace App\Module\Delivery\Queries;

use App\Module\Delivery\Contracts\Queries\DeliveryQuery as DeliveryQueryContract;
use App\Module\Delivery\DTO\DeliveryReportDTO;
use App\Module\Delivery\DTO\DeliveryShowDTO;
use App\Module\Delivery\Models\Delivery;
use App\Module\Order\Enums\VerificationTypeEnum;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\CommentTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class DeliveryQuery implements DeliveryQueryContract
{
    public function getAllPaginated(DeliveryShowDTO $DTO): LengthAwarePaginator
    {
        return Delivery::query()
            ->with([
                'company',
                'courier',
                'refStatus',
                'status',
                'city',
                'container',
                'customer.sector',
                'invoice' => [
                    'order',
                    'scan',
                    'files',
                    'waitListStatuses' => fn(MorphMany $q): MorphMany => $q->orderByDesc('id')
                        ->with(['user', 'refStatus', 'client']),
                    'statuses'         => fn (HasMany $q): HasMany => $q->select(['id', 'invoice_id', 'code', 'created_at'])
                ]
            ])
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->notInStatusIds, fn(Builder $query) => $query->whereNotIn('status_id', $DTO->notInStatusIds))
            ->when($DTO->waitListStatusIds, fn(Builder $query) => $query->whereHas('invoice.waitListStatuses.refStatus', function (Builder $query) use ($DTO) {
                $query->whereIn('id', $DTO->waitListStatusIds);
            }))
            ->when($DTO->invoiceNumber, fn(Builder $query) => $query->whereRelation('invoice', 'invoice_number', $DTO->invoiceNumber))
            ->when($DTO->courierId, fn(Builder $query) => $query->where('courier_id', $DTO->courierId))
            ->when($DTO->sectorId, fn(Builder $query) => $query->whereRelation('customer', 'sector_id', $DTO->sectorId))
            ->when($DTO->companyId, fn(Builder $query) => $query->whereRelation('company', 'id', $DTO->companyId))
            ->when($DTO->address, fn(Builder $query) => $query->whereRelation('customer', 'address', 'like', '%' . $DTO->address . '%'))
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->whereRelation('customer', 'dispatcher_sector_id', $DTO->dispatcherSectorId))
            ->when($DTO->containerId, fn(Builder $query) => $query->whereRelation('container', 'containers.id', $DTO->containerId))
            ->when($DTO->createdAtFrom, fn(Builder $query) => $query->whereDate('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtTo, fn(Builder $query) => $query->whereDate('created_at', '<=', $DTO->createdAtTo))
            ->when(
                $DTO->waitListComment && $DTO->waitListComment != 'other',
                fn(Builder $query) => $query->whereRelation('invoice.lastWaitListStatus', 'comment', $DTO->waitListComment)
            )->when(
                $DTO->waitListComment == 'other',
                function (Builder $query) {
                    $comments = CommentTemplate::query()->where('type_id', CommentTemplate::DELIVERY_TYPE_ID)->get()->pluck('text')->toArray();
                    return $query->whereRelation('invoice.lastWaitListStatus', function (Builder $query) use ($comments) {
                        $query->whereNotIn('comment', $comments);
                    });
                }
            )
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getByInternalId(int $id): ?Delivery
    {
        /** @var Delivery|null */
        return Delivery::query()->where('internal_id', $id)->first();
    }

    public function getByInvoiceId(int $id, string $sortDir = 'asc'): ?Delivery
    {
        /** @var Delivery|null */
        return Delivery::query()
            ->where('invoice_id', $id)
            ->orderBy('id', $sortDir)
            ->first();
    }

    public function getAllByInvoiceId(int $id, array $columns = ['*']): EloquentCollection
    {
        /** @var EloquentCollection */
        return Delivery::query()
            ->select($columns)
            ->where('invoice_id', $id)
            ->get();
    }

    public function getById(int $id): Delivery
    {
        /** @var Delivery */
        return Delivery::query()->findOrFail($id);
    }

    public function getByDispatcherSectorAndCreatedInterval(
        ?int $dispatcherSectorId,
        string $createdAtFrom,
        string $createdAtTo,
        array $columns = ['*'],
        array $relations = []
    ): Collection {
        /** @var Collection */
        return Delivery::query()
            ->select($columns)
            ->whereHas('customer', function (Builder $query) use ($dispatcherSectorId) {
                $query->when($dispatcherSectorId, fn($q) => $q->where('dispatcher_sector_id', $dispatcherSectorId));
            })
            ->whereHas('courier')
            ->whereDate('created_at', '>=', $createdAtFrom)
            ->whereDate('created_at', '<=', $createdAtTo)
            ->with($relations)
            ->get();
    }

    public function getAllCollection(DeliveryReportDTO $DTO, array $columns = ['*'], array $relations = []): Collection
    {
        /** @var Collection */
        return Delivery::query()
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->notInStatusIds, fn(Builder $query) => $query->whereNotIn('status_id', $DTO->notInStatusIds))
            ->when($DTO->waitListStatusIds, fn(Builder $query) => $query->whereIn('wait_list_status_id', $DTO->waitListStatusIds))
            ->when($DTO->invoiceNumber, fn(Builder $query) => $query->whereRelation('invoice', 'invoice_number', $DTO->invoiceNumber))
            ->when($DTO->courierId, fn(Builder $query) => $query->where('courier_id', $DTO->courierId))
            ->when($DTO->sectorId, fn(Builder $query) => $query->whereRelation('customer', 'sector_id', $DTO->sectorId))
            ->when($DTO->companyId, fn(Builder $query) => $query->whereRelation('company', 'id', $DTO->companyId))
            ->when($DTO->address, fn(Builder $query) => $query->whereRelation('customer', 'address', 'like', '%' . $DTO->address . '%'))
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->whereRelation('customer', 'dispatcher_sector_id', $DTO->dispatcherSectorId))
            ->when($DTO->createdAtFrom, fn(Builder $query) => $query->whereDate('created_at', '>=', $DTO->createdAtFrom))
            ->when($DTO->createdAtTo, fn(Builder $query) => $query->whereDate('created_at', '<=', $DTO->createdAtTo))
            ->when(
                $DTO->waitListComment && $DTO->waitListComment != 'other',
                fn(Builder $query) => $query->whereRelation('invoice.lastWaitListStatus', 'comment', $DTO->waitListComment)
            )->when(
                $DTO->waitListComment == 'other',
                function (Builder $query) {
                    $comments = CommentTemplate::query()->where('type_id', CommentTemplate::DELIVERY_TYPE_ID)->get()->pluck('text')->toArray();
                    return $query->whereRelation('invoice.lastWaitListStatus', function (Builder $query) use ($comments) {
                        $query->whereNotIn('comment', $comments);
                    });
                }
            )
            ->with($relations)
            ->orderByDesc('id')
            ->get();
    }

    public function getByInvoiceNumberAndVerify(string $invoiceNumber, int $verify): Collection
    {
        /** @var Collection */
        return Delivery::query()
            ->when(
                $verify === VerificationTypeEnum::SPARK_VERIFICATION_TYPE_ID->value,
                function (Builder $query) use ($invoiceNumber, $verify) {
                    return $query->whereHas('invoice', function (Builder $query) use ($invoiceNumber, $verify) {
                        $query->where('invoice_number', $invoiceNumber)
                            ->where('verify', $verify);
                    });
                },
                function (Builder $query) use ($invoiceNumber, $verify) {
                    return $query->whereHas('invoice', function (Builder $query) use ($invoiceNumber, $verify) {
                        $query->where('dop_invoice_number', $invoiceNumber)
                            ->where('verify', $verify);
                    });
                }
            )
            ->get();
    }
}
