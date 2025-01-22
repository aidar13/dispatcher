<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\OrderTake\IntegrationOneC;

use Illuminate\Contracts\Queue\ShouldQueue;

final class ChangeOrderTakeStatusInOneCCommand implements ShouldQueue
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}
