<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Contracts\Services;

use Illuminate\Support\Collection;

interface GetUserEmailService
{
    public function getDispatchers(array $userIds): Collection;
}
