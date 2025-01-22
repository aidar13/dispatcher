<?php

declare(strict_types=1);

namespace App\Module\Routing\Commands;

use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateSectorInYandexCommand implements ShouldQueue
{
    public string $queue = 'routing';

    public function __construct(public int $id)
    {
    }
}
