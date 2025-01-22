<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Queries;

use App\Module\Status\Models\OrderStatus;

interface OrderStatusQuery
{
    public function getById(int $id): OrderStatus;

    public function getLastByInvoiceId(int $invoiceId): ?OrderStatus;

    public function getStatusForWaveByInvoiceId(int $invoiceId): ?OrderStatus;

    public function getLastTakeStatusInvoiceIdAndDate(int $invoiceId, string $date): ?OrderStatus;
}
