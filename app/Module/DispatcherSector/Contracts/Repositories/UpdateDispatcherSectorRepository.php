<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\DispatcherSector;

interface UpdateDispatcherSectorRepository
{
    public function update(DispatcherSector $dispatcherSector): void;
}
