<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Repositories;

use App\Module\Planning\Models\ContainerInvoice;

interface DeleteContainerInvoiceRepository
{
    public function delete(ContainerInvoice $containerInvoice): void;
}
