<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\DTO\DispatcherSectorShowDTO;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface DispatcherSectorQuery
{
    public function getById(int $id): DispatcherSector;

    public function getAllDispatcherSectorsActiveUsers(array $userIds): Collection|array;

    public function getAllPaginated(DispatcherSectorShowDTO $DTO): LengthAwarePaginator;
}
