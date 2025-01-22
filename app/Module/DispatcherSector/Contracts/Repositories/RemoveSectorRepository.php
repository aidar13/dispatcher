<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\Sector;

interface RemoveSectorRepository
{
    public function remove(Sector $sector): void;
}
