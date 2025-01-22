<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Repositories;

use App\Module\Routing\Models\RoutingItem;

interface CreateRoutingItemRepository
{
    public function create(RoutingItem $model): void;
}
