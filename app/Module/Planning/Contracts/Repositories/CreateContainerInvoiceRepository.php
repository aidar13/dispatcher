<?php

declare(strict_types=1);

namespace App\Module\Planning\Contracts\Repositories;

use App\Module\Planning\Models\ContainerInvoice;

interface CreateContainerInvoiceRepository
{
    public function create(ContainerInvoice $containerInvoice): void;
}
