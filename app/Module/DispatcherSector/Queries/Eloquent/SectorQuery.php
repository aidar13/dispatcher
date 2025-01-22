<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Eloquent;

use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery as SectorQueryContract;
use App\Module\DispatcherSector\DTO\SectorShowDTO;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class SectorQuery implements SectorQueryContract, SectorPolygonQuery
{
    public function getAllSectorsPaginated(SectorShowDTO $DTO): LengthAwarePaginator
    {
        return Sector::query()
            ->with(['dispatcherSector:id,name'])
            ->when($DTO->name, fn(Builder $query) => $query->where('name', 'like', '%' . $DTO->name . '%'))
            ->when($DTO->dispatcherSectorIds, fn(Builder $query) => $query->whereIn('dispatcher_sector_id', $DTO->dispatcherSectorIds))
            ->when($DTO->cityId, fn(Builder $query) => $query->whereRelation('dispatcherSector', 'city_id', $DTO->cityId))
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getById(int $id): Sector
    {
        /** @var Sector */
        return Sector::query()->where('id', $id)->firstOrFail();
    }

    public function findByCoordinates(?string $latitude, ?string $longitude): ?Sector
    {
        /** @var Sector|null */
        return Sector::query()
            ->whereNull('deleted_at')
            ->whereRaw(
                "st_intersects(ST_GEOMFROMTEXT(concat('POLYGON((', sectors.polygon, '))')), POINT(?, ?))",
                [$latitude, $longitude]
            )
            ->first();
    }

    public function getAllByDispatcherSectorIdAndIds(int $dispatcherSectorId, ?array $ids = null): Collection
    {
        return Sector::query()
            ->select(['id', 'name', 'dispatcher_sector_id'])
            ->where('dispatcher_sector_id', $dispatcherSectorId)
            ->when($ids, fn(Builder $query) => $query->whereIn('id', $ids))
            ->get();
    }
}
