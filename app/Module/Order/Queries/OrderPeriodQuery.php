<?php

declare(strict_types=1);

namespace App\Module\Order\Queries;

use App\Module\Take\Contracts\Queries\OrderPeriodQuery as OrderPeriodQueryContract;
use App\Module\Take\DTO\OrderPeriodDTO;
use App\Module\Take\Models\OrderPeriod;
use Illuminate\Database\Eloquent\Collection;

final class OrderPeriodQuery implements OrderPeriodQueryContract
{
    public function getAll(OrderPeriodDTO $DTO): Collection|array
    {
        return OrderPeriod::query()
            ->get();
    }
}
