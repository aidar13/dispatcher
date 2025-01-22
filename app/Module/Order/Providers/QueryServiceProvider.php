<?php

declare(strict_types=1);

namespace App\Module\Order\Providers;

use App\Module\Order\Contracts\Queries\AdditionalServiceValueQuery as AdditionalServiceValueQueryContract;
use App\Module\Order\Contracts\Queries\FastDeliveryOrderQuery as FastDeliveryOrderQueryContract;
use App\Module\Order\Contracts\Queries\InvoiceCargoQuery as InvoiceCargoQueryContract;
use App\Module\Order\Contracts\Queries\InvoiceQuery as InvoiceQueryContract;
use App\Module\Order\Contracts\Queries\OrderQuery as OrderQueryContract;
use App\Module\Order\Contracts\Queries\ReceiverQuery as ReceiverQueryContract;
use App\Module\Order\Contracts\Queries\SenderQuery as SenderQueryContract;
use App\Module\Order\Contracts\Queries\SlaQuery as SlaQueryContract;
use App\Module\Order\Queries\AdditionalServiceValueQuery;
use App\Module\Order\Queries\FastDeliveryOrderQuery;
use App\Module\Order\Queries\OrderPeriodQuery;
use App\Module\Take\Contracts\Queries\OrderPeriodQuery as OrderPeriodQueryContract;
use App\Module\Order\Queries\InvoiceCargoQuery;
use App\Module\Order\Queries\InvoiceQuery;
use App\Module\Order\Queries\OrderQuery;
use App\Module\Order\Queries\ReceiverQuery;
use App\Module\Order\Queries\SenderQuery;
use App\Module\Order\Queries\SlaQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        SenderQueryContract::class                 => SenderQuery::class,
        OrderQueryContract::class                  => OrderQuery::class,
        ReceiverQueryContract::class               => ReceiverQuery::class,
        InvoiceQueryContract::class                => InvoiceQuery::class,
        InvoiceCargoQueryContract::class           => InvoiceCargoQuery::class,
        SlaQueryContract::class                    => SlaQuery::class,
        OrderPeriodQueryContract::class            => OrderPeriodQuery::class,
        FastDeliveryOrderQueryContract::class      => FastDeliveryOrderQuery::class,
        AdditionalServiceValueQueryContract::class => AdditionalServiceValueQuery::class,
    ];
}
