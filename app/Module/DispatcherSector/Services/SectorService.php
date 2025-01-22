<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Services;

use App\Module\DispatcherSector\Contracts\Queries\SectorQuery;
use App\Module\DispatcherSector\Contracts\Services\SectorService as SectorServiceContract;
use App\Module\DispatcherSector\DTO\SectorShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class SectorService implements SectorServiceContract
{
    public function __construct(
        private readonly SectorQuery $sectorQuery,
    ) {
    }

    public function getAllSectorsPaginated(SectorShowDTO $DTO): LengthAwarePaginator
    {
        return $this->sectorQuery->getAllSectorsPaginated($DTO);
    }
}
