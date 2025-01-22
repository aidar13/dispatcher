<?php

declare(strict_types=1);

namespace App\Module\Order\Contracts\Queries;

use App\Module\DispatcherSector\DTO\WaveShowDTO;
use App\Module\Order\Models\Invoice;
use App\Module\Order\DTO\InvoiceShowDTO;
use Illuminate\Database\Eloquent\Collection;

interface InvoiceQuery
{
    public function getById(int $id, array $columns = ['*'], array $relations = []): ?Invoice;

    public function getWaveInvoices(WaveShowDTO $DTO): Collection;

    public function getInvoices(InvoiceShowDTO $DTO): Collection;

    public function getByInvoiceNumber(string $invoiceNumber): Invoice;
}
