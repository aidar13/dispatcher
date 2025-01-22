<?php

declare(strict_types=1);

namespace App\Module\Delivery\Queries;

use App\Module\Delivery\Contracts\Queries\ReturnDeliveryQuery as ReturnDeliveryQueryContract;
use App\Module\Delivery\Models\ReturnDelivery;
use Illuminate\Database\Eloquent\Collection;

final class ReturnDeliveryQuery implements ReturnDeliveryQueryContract
{
    public function getByInvoiceId(int $id): Collection
    {
        /** @var Collection */
        return ReturnDelivery::query()
            ->where('invoice_id', $id)
            ->get();
    }
}
