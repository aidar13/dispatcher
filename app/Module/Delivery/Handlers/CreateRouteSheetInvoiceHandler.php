<?php

declare(strict_types=1);

namespace App\Module\Delivery\Handlers;

use App\Module\Delivery\Commands\CreateRouteSheetInvoiceCommand;
use App\Module\Delivery\Contracts\Repositories\CreateRouteSheetInvoiceRepository;
use App\Module\Delivery\Models\RouteSheetInvoice;

final class CreateRouteSheetInvoiceHandler
{
    public function __construct(
        private readonly CreateRouteSheetInvoiceRepository $repository,
    ) {
    }

    public function handle(CreateRouteSheetInvoiceCommand $command): void
    {
        $routeSheetInvoice                 = new RouteSheetInvoice();
        $routeSheetInvoice->route_sheet_id = $command->routeSheetId;
        $routeSheetInvoice->invoice_id     = $command->invoiceId;

        $this->repository->create($routeSheetInvoice);
    }
}
