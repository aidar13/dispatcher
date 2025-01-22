<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateDefaultSectorCommand implements ShouldQueue
{
    public function __construct(public readonly int $dispatcherSectorId)
    {
    }
}
