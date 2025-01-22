<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Providers;

use App\Module\CourierApp\Contracts\Queries\CourierLocation\CourierLocationQuery as CourierLocationQueryContract;
use App\Module\CourierApp\Contracts\Queries\Delivery\CourierDeliveryQuery as CourierDeliveryQueryContract;
use App\Module\CourierApp\Contracts\Queries\OrderTake\CourierOrderTakeQuery as CourierOrderTakeQueryContract;
use App\Module\CourierApp\Queries\CourierLocation\CourierLocationQuery;
use App\Module\CourierApp\Queries\Delivery\CourierDeliveryQuery;
use App\Module\CourierApp\Queries\OrderTake\CourierOrderTakeQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CourierOrderTakeQueryContract::class => CourierOrderTakeQuery::class,
        CourierDeliveryQueryContract::class  => CourierDeliveryQuery::class,
        CourierLocationQueryContract::class  => CourierLocationQuery::class,
    ];
}
