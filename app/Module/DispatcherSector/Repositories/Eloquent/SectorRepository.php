<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Eloquent;

use App\Module\DispatcherSector\Contracts\Repositories\CreateSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateSectorRepository;
use App\Module\DispatcherSector\Models\Sector;
use Throwable;

final class SectorRepository implements CreateSectorRepository, UpdateSectorRepository, RemoveSectorRepository
{
    /**
     * @throws Throwable
     */
    public function create(Sector $sector): void
    {
        $sector->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Sector $sector): void
    {
        $sector->updateOrFail();
    }

    /**
     * @throws Throwable
     */
    public function remove(Sector $sector): void
    {
        $sector->delete();
    }
}
