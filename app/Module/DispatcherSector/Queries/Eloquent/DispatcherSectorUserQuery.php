<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Eloquent;

use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery as DispatchersSectorUserQueryContract;
use App\Module\DispatcherSector\Models\DispatchersSectorUser;
use Illuminate\Database\Eloquent\Collection;

final class DispatcherSectorUserQuery implements DispatchersSectorUserQueryContract
{
    public function getByDispatcherSectorId(int $dispatcherSectorId): Collection|array
    {
        return DispatchersSectorUser::query()
            ->where('dispatcher_sector_id', $dispatcherSectorId)
            ->get();
    }

    public function getAllDispatcherSectorUserIdsByCityId(int $cityId): array
    {
        return DispatchersSectorUser::query()
            ->whereRelation('dispatcherSector', 'city_id', $cityId)
            ->get()
            ->pluck('user_id')
            ->toArray();
    }

    public function getByCiyId(int $cityId): Collection
    {
        /** @var Collection */
        return DispatchersSectorUser::query()
            ->whereHas('dispatcherSector', function ($query) use ($cityId) {
                $query->where('city_id', $cityId);
            })
            ->get();
    }
}
