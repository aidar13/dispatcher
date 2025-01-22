<?php

declare(strict_types=1);

namespace App\Module\Delivery\Events;

final class RouteSheetCreatedFromOneCEvent
{
    public function __construct(
        public readonly int $routeSheetId,
        public readonly int $courierId
    ) {
    }
}
