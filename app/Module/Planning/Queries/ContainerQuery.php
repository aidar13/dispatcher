<?php

declare(strict_types=1);

namespace App\Module\Planning\Queries;

use App\Module\Planning\Contracts\Queries\ContainerQuery as ContainerQueryContract;
use App\Module\Planning\DTO\ContainerPaginationDTO;
use App\Module\Planning\DTO\ContainerShowDTO;
use App\Module\Planning\DTO\SendToAssemblyDTO;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerStatus;
use App\Module\Status\Models\RefStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class ContainerQuery implements ContainerQueryContract
{
    public function getContainersPaginated(ContainerPaginationDTO $DTO): LengthAwarePaginator
    {
        return $this->initQuery($DTO)
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getById(int $id, array $columns = ['*'], array $relations = []): Container
    {
        /** @var Container */
        return Container::query()
            ->select($columns)
            ->with($relations)
            ->findOrFail($id);
    }

    public function getAllByIds(array $ids, array $columns = ['*'], array $relations = []): Collection
    {
        /** @var Collection */
        return Container::query()
            ->whereIn('id', $ids)
            ->select($columns)
            ->with($relations)
            ->get();
    }

    public function checkSectorHasContainer(int $sectorId, int $waveId, string $date): bool
    {
        return Container::query()
            ->where('sector_id', $sectorId)
            ->where('wave_id', $waveId)
            ->where('date', $date)
            ->exists();
    }

    public function getLastId(): ?Container
    {
        /** @var Container|null */
        return Container::query()
            ->orderByDesc('id')
            ->first();
    }

    public function getAllContainers(ContainerShowDTO $DTO): Collection
    {
        /** @var Collection */
        return $this->initQuery($DTO)
            ->get();
    }

    public function getAllContainersToAssembly(SendToAssemblyDTO $DTO): Collection
    {
        return Container::query()
            ->with([
                'invoices',
                'wave:id,from_time,to_time'
            ])
            ->whereHas('invoices')
            ->whereHas('courier')
            ->whereIn('status_id', [ContainerStatus::ID_COURIER_APPOINTED, ContainerStatus::ID_FAST_DELIVERY_SELECTED])
            ->when(
                $DTO->containerIds == [],
                fn(Builder $query) => $query
                    ->where('wave_id', $DTO->waveId)
                    ->where('date', $DTO->date)
                    ->when($DTO->sectorIds, fn(Builder $query) => $query->whereIn('sector_id', $DTO->sectorIds)),
                fn(Builder $query) => $query
                    ->whereIn('id', $DTO->containerIds)
            )
            ->get();
    }

    public function getFastDeliveryByContainers(array $containerIds): Collection
    {
        /** @var Collection */
        return Container::query()
            ->has('fastDeliveryOrder')
            ->whereIn('id', $containerIds)
            ->get();
    }

    public function getByCourierIdForRouting(int $courierId): ?Container
    {
        /** @var ?Container */
        return Container::query()
            ->with(['invoices' => function ($query) {
                $query->whereNot('invoices.status_id', RefStatus::ID_DELIVERED);
            }])
            ->where('courier_id', $courierId)
            ->whereNotNull('routing_id')
            ->orderByDesc('id')
            ->first();
    }

    private function initQuery(ContainerShowDTO|ContainerPaginationDTO $DTO): Builder
    {
        return Container::query()
            ->with(['status', 'invoices', 'invoices.cargo', 'fastDeliveryOrder'])
            ->when(
                $DTO->invoiceNumber,
                fn(Builder $query) => $query->whereRelation('invoices', 'invoice_number', 'like', '%' . $DTO->invoiceNumber . '%')
            )
            ->when(
                $DTO->deliveryStatusIds,
                fn(Builder $query) => $query->whereRelation(
                    'invoices',
                    fn(Builder $q) => $q->whereHas(
                        'deliveries',
                        fn(Builder $dq) => $dq->whereIn('status_id', $DTO->deliveryStatusIds)
                    )
                )
            )
            ->when($DTO->userId, fn(Builder $query) => $query->where('user_id', $DTO->userId))
            ->when($DTO->courierId, fn(Builder $query) => $query->where('courier_id', $DTO->courierId))
            ->when($DTO->title, fn(Builder $query) => $query->where('title', 'like', '%' . $DTO->title . '%'))
            ->when($DTO->sectorId, fn(Builder $query) => $query->where('sector_id', $DTO->sectorId))
            ->when($DTO->waveId, fn(Builder $query) => $query->where('wave_id', $DTO->waveId))
            ->when($DTO->statusId, fn(Builder $query) => $query->where('status_id', $DTO->statusId))
            ->when($DTO->statusIds, fn(Builder $query) => $query->whereIn('status_id', $DTO->statusIds))
            ->when($DTO->sectorIds, fn(Builder $query) => $query->whereIn('sector_id', $DTO->sectorIds))
            ->when($DTO->date, fn(Builder $query) => $query->where('date', $DTO->date))
            ->when($DTO->dateFrom, fn(Builder $query) => $query->where('date', '>=', $DTO->dateFrom))
            ->when($DTO->dateTo, fn(Builder $query) => $query->where('date', '<=', $DTO->dateTo))
            ->when($DTO->cargoType, fn(Builder $query) => $query->whereIn('cargo_type', $DTO->cargoType));
    }
}
