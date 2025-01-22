<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Repositories;

use App\Module\DispatcherSector\Models\DispatchersSectorUser;

interface AttachDispatcherSectorUsersRepository
{
    public function attachUsers(DispatchersSectorUser $dispatchersSectorUser): void;
}
