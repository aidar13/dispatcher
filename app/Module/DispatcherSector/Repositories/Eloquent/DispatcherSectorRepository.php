<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Eloquent;

use App\Module\DispatcherSector\Contracts\Repositories\AttachDispatcherSectorUsersRepository;
use App\Module\DispatcherSector\Contracts\Repositories\CreateDispatcherSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\RemoveDispatcherSectorRepository;
use App\Module\DispatcherSector\Contracts\Repositories\UpdateDispatcherSectorRepository;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\DispatchersSectorUser;
use Throwable;

final class DispatcherSectorRepository implements CreateDispatcherSectorRepository, UpdateDispatcherSectorRepository, RemoveDispatcherSectorRepository, AttachDispatcherSectorUsersRepository
{
    /**
     * @throws Throwable
     */
    public function create(DispatcherSector $dispatcherSector): void
    {
        $dispatcherSector->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(DispatcherSector $dispatcherSector): void
    {
        $dispatcherSector->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function remove(DispatcherSector $dispatcherSector): void
    {
        $dispatcherSector->delete();
    }

    /**
     * @throws Throwable
     */
    public function attachUsers(DispatchersSectorUser $dispatchersSectorUser): void
    {
        $dispatchersSectorUser->saveOrFail();
    }
}
