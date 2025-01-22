<?php

declare(strict_types=1);

namespace App\Module\Status\Contracts\Services;

use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

interface TakeStatusService
{
    public function getCurrentStatusKey(OrderTake $take): string;
    public function getStatusHistory(OrderTake $take): Collection;

    public function getTakeStatusByInvoiceIdAndDate(int $invoiceId, ?Carbon $date = null): int;
}
