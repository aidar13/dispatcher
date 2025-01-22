<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use Illuminate\Database\Eloquent\Collection;

interface DispatchersSectorUserQuery
{
    public function getByDispatcherSectorId(int $dispatcherSectorId): Collection|array;

    public function getAllDispatcherSectorUserIdsByCityId(int $cityId): array;

    public function getByCiyId(int $cityId): Collection;
}
