<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Cache;

use App\Constants\CacheConstants;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery as DispatcherSectorContract;
use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class DispatcherSectorQuery implements DispatcherSectorContract
{
    public function __construct(private readonly DispatcherSectorContract $query)
    {
    }

    public function getById(int $id): DispatcherSector
    {
        return $this->query->getById($id);
    }

    public function getAllDispatcherSectorsActiveUsers(array $userIds): Collection|array
    {
        return Cache::remember(
            CacheConstants::DISPATCHER_SECTOR_ALL_CACHE_KEY,
            CacheConstants::CACHE_TTL_DAY,
            fn() => $this->query->getAllDispatcherSectorsActiveUsers($userIds)
        );
    }

    public function getAllPaginated(DispatcherSectorShowDTO $DTO): LengthAwarePaginator
    {
        return $this->query->getAllPaginated($DTO);
    }
}
