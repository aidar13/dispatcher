<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

use App\Module\DispatcherSector\DTO\IntegrationSectorDTO;

interface CreateSectorIntegrationRepository
{
    public function create(IntegrationSectorDTO $DTO): void;
}
