<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Repositories;

use App\Module\DispatcherSector\Models\Sector;

interface IntegrationZoneRepository
{
    public function create(Sector $sector): void;

    public function update(Sector $sector): void;
    public function delete(Sector $sector): void;
}
