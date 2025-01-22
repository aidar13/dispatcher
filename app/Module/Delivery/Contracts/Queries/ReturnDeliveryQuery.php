<?php

declare(strict_types=1);

namespace App\Module\Delivery\Contracts\Queries;

use Illuminate\Database\Eloquent\Collection;

interface ReturnDeliveryQuery
{
    public function getByInvoiceId(int $id): Collection;
}
