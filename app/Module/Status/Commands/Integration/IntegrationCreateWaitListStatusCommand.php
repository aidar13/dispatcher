<?php

declare(strict_types=1);

namespace App\Module\Status\Commands\Integration;

use App\Module\Status\DTO\Integration\IntegrationCreateWaitListStatusDTO;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class IntegrationCreateWaitListStatusCommand implements ShouldQueue
{
    public function __construct(
        public IntegrationCreateWaitListStatusDTO $DTO,
    ) {
    }
}
