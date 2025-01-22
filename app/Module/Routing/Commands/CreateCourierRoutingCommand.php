<?php

declare(strict_types=1);

namespace App\Module\Routing\Commands;

use App\Module\Routing\DTO\CreateCourierRoutingDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateCourierRoutingCommand implements ShouldQueue
{
    public string $queue = 'routing';

    public function __construct(public CreateCourierRoutingDTO $DTO)
    {
    }
}
