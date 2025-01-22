<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Queries;

use App\Module\DispatcherSector\DTO\WarehouseDTO;

interface HttpWarehouseQuery
{
    public function getByCityId(int $cityId): ?WarehouseDTO;
}
