<?php

declare(strict_types=1);

namespace App\Module\Order\Commands\Integration;

use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class UpdateInvoiceSectorsInCabinetCommand implements ShouldQueue
{
    public function __construct(
        public int $id
    ) {
    }
}
