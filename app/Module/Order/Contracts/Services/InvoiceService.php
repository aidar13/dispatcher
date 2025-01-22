<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Services;

use App\Module\Order\DTO\InvoicesDTO;
use App\Module\Order\DTO\InvoiceShowDTO;
use App\Module\Order\Models\Invoice;
use Illuminate\Support\Collection;

interface InvoiceService
{
    public function getInvoices(InvoiceShowDTO $DTO): InvoicesDTO;

    public function getProblems(Invoice $invoice): Collection;

    public function getInvoiceProblemsById(int $invoiceId): Invoice;

    public function getById(int $invoiceId, array $columns = ['*'], array $relations = []): ?Invoice;
}
