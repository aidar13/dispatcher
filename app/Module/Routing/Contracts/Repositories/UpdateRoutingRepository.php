<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Repositories;

use App\Module\Routing\Models\Routing;

interface UpdateRoutingRepository
{
    public function update(Routing $model): void;
}
