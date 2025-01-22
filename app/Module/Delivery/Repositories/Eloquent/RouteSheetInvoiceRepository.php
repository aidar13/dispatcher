<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\Eloquent;

use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetInvoiceRepository;
use App\Module\Delivery\Models\RouteSheetInvoice;
use Throwable;

final class RouteSheetInvoiceRepository implements CreateRouteSheetInvoiceRepository
{
    /**
     * @throws Throwable
     */
    public function create(RouteSheetInvoice $routeSheetInvoice): void
    {
        $routeSheetInvoice->saveOrFail();
    }
}
