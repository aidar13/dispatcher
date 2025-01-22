<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

final class UpdateRouteSheetCommand
{
    public function __construct(
        public readonly int $deliveryId,
    ) {
    }
}
