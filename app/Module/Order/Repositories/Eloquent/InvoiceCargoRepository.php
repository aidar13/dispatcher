<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Eloquent;

use App\Module\Order\Contracts\Repositories\CreateInvoiceCargoRepository;
use App\Module\Order\Contracts\Repositories\UpdateInvoiceCargoRepository;
use App\Module\Order\Models\InvoiceCargo;
use Throwable;

final class InvoiceCargoRepository implements CreateInvoiceCargoRepository, UpdateInvoiceCargoRepository
{
    /**
     * @throws Throwable
     */
    public function create(InvoiceCargo $invoiceInfo): void
    {
        $invoiceInfo->saveOrFail();
    }

    /**
     * @throws Throwable
     */
    public function update(InvoiceCargo $invoiceInfo): void
    {
        $invoiceInfo->saveOrFail();
    }
}
