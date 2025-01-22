<?php

declare(strict_types=1);

namespace App\Module\Routing\Queries;

use App\Module\Routing\Contracts\Queries\RoutingQuery as RoutingQueryContract;
use App\Module\Routing\Models\Routing;

final class RoutingQuery implements RoutingQueryContract
{
    public function getById(int $id): Routing
    {
        /** @var Routing */
        return Routing::query()->findOrFail($id);
    }

    public function getByCourierIdAndDate(int $id, string $date): ?Routing
    {
        /** @var Routing|null */
        return Routing::query()
            ->where('courier_id', $id)
            ->whereDate('created_at', $date)
            ->first();
    }
}
