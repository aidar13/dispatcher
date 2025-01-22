<?php

declare(strict_types=1);

namespace App\Module\Routing\Commands;

final readonly class CreateRoutingItemsCommand
{
    public function __construct(public int $routingId)
    {
    }
}
