<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

interface DestroySectorIntegrationRepository
{
    public function destroy(int $sectorId): void;
}
