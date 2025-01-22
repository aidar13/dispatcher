<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\Wave;

interface UpdateWaveRepository
{
    public function update(Wave $wave): void;
}
