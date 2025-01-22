<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Eloquent;

use App\Module\DispatcherSector\Contracts\Repositories\CreateWaveRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveWaveRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateWaveRepository;
use App\Module\DispatcherSector\Models\Wave;
use Throwable;

final class WaveRepository implements CreateWaveRepository, RemoveWaveRepository, UpdateWaveRepository
{
    /**
     * @throws Throwable
     */
    public function create(Wave $wave): void
    {
        $wave->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Wave $wave): void
    {
        $wave->updateOrFail();
    }

    /**
     * @throws Throwable
     */
    public function remove(Wave $wave): void
    {
        $wave->delete();
    }
}
