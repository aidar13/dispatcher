<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

use App\Module\DispatcherSector\DTO\IntegrationDispatcherSectorDTO;

interface UpdateDispatcherSectorIntegrationRepository
{
    public function update(IntegrationDispatcherSectorDTO $DTO): void;
}
