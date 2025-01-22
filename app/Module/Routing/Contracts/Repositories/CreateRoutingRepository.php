<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Repositories;

use App\Module\Routing\Models\Routing;

interface CreateRoutingRepository
{
    public function create(Routing $model): void;
}
