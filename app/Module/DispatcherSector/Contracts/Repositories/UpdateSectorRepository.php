<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\Sector;

interface UpdateSectorRepository
{
    public function update(Sector $sector): void;
}
