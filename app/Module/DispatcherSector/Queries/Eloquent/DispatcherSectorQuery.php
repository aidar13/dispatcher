<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Eloquent;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery as DispatcherSectorQueryContract;
use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

final class DispatcherSectorQuery implements DispatcherSectorQueryContract, DispatcherSectorPolygonQuery
{
    public function getById(int $id): DispatcherSector
    {
        /** @var DispatcherSector */
        return DispatcherSector::query()
            ->findOrFail($id);
    }

    public function getAllDispatcherSectorsActiveUsers(array $userIds): Collection|array
    {
        return DispatcherSector::query()
            ->with([
                'city:id,name,region_id,type_id,code,coordinates,latitude,longitude',
                'sectors:id,name,dispatcher_sector_id,coordinates,polygon,color',
                'dispatcherSectorUsers' => function ($query) use ($userIds) {
                    $query
                        ->with(['user'])
                        ->whereIn('user_id', $userIds);
                },
            ])
            ->orderByDesc('id')
            ->get();
    }

    public function getAllPaginated(DispatcherSectorShowDTO $DTO): LengthAwarePaginator
    {
        return DispatcherSector::query()
            ->with([
                'city:id,name,region_id,type_id,code,coordinates,latitude,longitude',
                'sectors:id,name,dispatcher_sector_id,coordinates,polygon,color',
                'dispatcherSectorUsers:id,dispatcher_sector_id,user_id',
                'dispatcherSectorUsers.user',
            ])
            ->when($DTO->name, fn(Builder $query) => $query->where('name', 'like', '%' . $DTO->name . '%'))
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function findByCoordinates(?string $latitude, ?string $longitude): ?DispatcherSector
    {
        /** @var DispatcherSector|null */
        return DispatcherSector::query()
            ->whereNull('deleted_at')
            ->whereRaw(
                "st_intersects(ST_GEOMFROMTEXT(concat('POLYGON((', dispatcher_sectors.polygon, '))')), POINT(?, ?))",
                [$latitude, $longitude]
            )
            ->first();
    }
}
