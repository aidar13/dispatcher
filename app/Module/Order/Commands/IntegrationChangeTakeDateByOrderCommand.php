<?php

declare(strict_types=1);

namespace App\Module\Order\Commands;

use App\Module\Take\DTO\ChangeTakeDateDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final class IntegrationChangeTakeDateByOrderCommand implements ShouldQueue
{
    public string $queue = 'change-take-date';

    public function __construct(
        public readonly ChangeTakeDateDTO $DTO
    ) {
    }
}
