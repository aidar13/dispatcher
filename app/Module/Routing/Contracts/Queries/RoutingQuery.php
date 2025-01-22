<?php

declare(strict_types=1);

namespace App\Module\Routing\Contracts\Queries;

use App\Module\Routing\Models\Routing;

interface RoutingQuery
{
    public function getById(int $id): Routing;

    public function getByCourierIdAndDate(int $id, string $date): ?Routing;
}
