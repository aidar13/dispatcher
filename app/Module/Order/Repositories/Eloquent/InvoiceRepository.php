<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateInvoiceRepository;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceRepository;
use App\Module\Order\Models\Invoice;
use Throwable;

final class InvoiceRepository implements CreateInvoiceRepository, UpdateInvoiceRepository
{
    /**
     * @throws Throwable
     */
    public function create(Invoice $invoice): void
    {
        $invoice->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(Invoice $invoice): void
    {
        $invoice->saveOrFail();
    }
}
