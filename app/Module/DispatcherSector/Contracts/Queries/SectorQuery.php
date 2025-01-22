<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\DTO\SectorShowDTO;
use App\Module\DispatcherSector\Models\Sector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SectorQuery
{
    public function getAllSectorsPaginated(SectorShowDTO $DTO): LengthAwarePaginator;

    public function getAllByDispatcherSectorIdAndIds(int $dispatcherSectorId, ?array $ids = null): Collection;

    public function getById(int $id): Sector;
}
