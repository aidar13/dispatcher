<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

use App\Module\DispatcherSector\DTO\IntegrationDispatcherSectorDTO;

interface CreateDispatcherSectorIntegrationRepository
{
    public function create(IntegrationDispatcherSectorDTO $DTO): void;
}
