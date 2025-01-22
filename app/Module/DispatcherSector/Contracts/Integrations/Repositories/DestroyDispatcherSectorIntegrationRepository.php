<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Integrations\Repositories;

interface DestroyDispatcherSectorIntegrationRepository
{
    public function destroy(int $dispatcherSectorId): void;
}
