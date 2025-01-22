<?php

declare(strict_types=1);

namespace Tests\Fake\Repositories;

use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\DispatcherSector\DTO\WarehouseDTO;

final class WarehouseFakerQuery implements HttpWarehouseQuery
{
    public function __construct(private readonly ?WarehouseDTO $DTO = null)
    {
    }

    public function getByCityId(int $cityId): ?WarehouseDTO
    {
        return $this->DTO;
    }
}
