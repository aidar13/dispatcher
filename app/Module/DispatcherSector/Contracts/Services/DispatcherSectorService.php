<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Services;

use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DispatcherSectorService
{
    public function getAllDispatcherSectorsPaginated(DispatcherSectorShowDTO $DTO): LengthAwarePaginator;

    public function getAllDispatcherSectorsActiveUsers(): Collection|array;
}
