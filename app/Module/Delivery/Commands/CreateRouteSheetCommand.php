<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

final class CreateRouteSheetCommand
{
    public function __construct(
        public readonly string $routeSheetNumber,
        public readonly int $invoiceId,
    ) {
    }
}
