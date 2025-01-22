<?php

declare(strict_types=1);

namespace App\Module\Routing\Commands;

final class CreateRoutingForDispatcherSectorCommand
{
    public function __construct(public int $id)
    {
    }
}
