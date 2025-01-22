<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\DispatcherSector;

interface CreateDispatcherSectorRepository
{
    public function create(DispatcherSector $dispatcherSector): void;
}
