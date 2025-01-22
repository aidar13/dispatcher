<?php

declare(strict_types=1);

namespace App\Module\Delivery\Commands;

use App\Module\Delivery\DTO\RouteSheetFrom1CDTO;

final class CreateRouteSheetFrom1CCommand
{
    public function __construct(
        public readonly RouteSheetFrom1CDTO $DTO,
    ) {
    }
}
