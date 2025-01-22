<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Repositories;

use App\Module\Delivery\Models\RouteSheetInvoice;

interface CreateRouteSheetInvoiceRepository
{
    public function create(RouteSheetInvoice $routeSheetInvoice): void;
}
