<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

use App\Module\DispatcherSector\DTO\IntegrationSectorDTO;

interface UpdateSectorIntegrationRepository
{
    public function update(IntegrationSectorDTO $DTO): void;
}
