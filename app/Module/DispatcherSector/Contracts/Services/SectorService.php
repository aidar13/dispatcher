<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Services;

use App\Module\DispatcherSector\DTO\SectorShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SectorService
{
    public function getAllSectorsPaginated(SectorShowDTO $DTO): LengthAwarePaginator;
}
