<?php

declare(strict_types=1);

namespace App\Module\Take\Commands;

use App\Module\Take\DTO\OrderTakeDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class UpdateOrderTakeCommand implements ShouldQueue
{
    public string $queue = 'dispatcherOrder';

    public function __construct(public readonly OrderTakeDTO $DTO)
    {
    }
}
