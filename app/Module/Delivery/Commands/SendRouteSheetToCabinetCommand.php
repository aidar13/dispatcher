<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class SendRouteSheetToCabinetCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public readonly int $routeSheetId)
    {
    }
}
