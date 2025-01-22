<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Repositories;

use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\Routing\DTO\IntegrationRoutingDTO;
use App\Module\Routing\Models\Routing;

interface IntegrationRoutingRepository
{
    public function create(Routing $routing, WarehouseDTO $DTO): void;

    public function getByTaskId(string $taskId): IntegrationRoutingDTO;
}
