<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\Wave;

interface RemoveWaveRepository
{
    public function remove(Wave $wave): void;
}
