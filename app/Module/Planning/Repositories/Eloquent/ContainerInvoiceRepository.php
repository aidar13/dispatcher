<?php

declare(strict_types=1);

namespace App\Module\Planning\Repositories\Eloquent;

use App\Module\Planning\Contracts\Repositories\CreateContainerInvoiceRepository;
use App\Module\Planning\Contracts\Repositories\DeleteContainerInvoiceRepository;
use App\Module\Planning\Contracts\Repositories\UpdateContainerInvoiceRepository;
use App\Module\Planning\Models\ContainerInvoice;
use Throwable;

final class ContainerInvoiceRepository implements CreateContainerInvoiceRepository, DeleteContainerInvoiceRepository, UpdateContainerInvoiceRepository
{
    /**
     * @throws Throwable
     */
    public function create(ContainerInvoice $containerInvoice): void
    {
        $containerInvoice->saveOrFail();
    }

    public function delete(ContainerInvoice $containerInvoice): void
    {
        $containerInvoice->delete();
    }

    /**
     * @throws Throwable
     */
    public function update(ContainerInvoice $containerInvoice): void
    {
        $containerInvoice->saveOrFail();
    }
}
