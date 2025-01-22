<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\CreateContainerDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CreateContainerCommand implements ShouldQueue
{
    public string $queue = 'container';

    public function __construct(
        public readonly int $userId,
        public readonly CreateContainerDTO $DTO
    ) {
    }
}
