<?php

declare(strict_types=1);

namespace App\Module\Status\Providers;

use App\Module\Status\Contracts\Queries\OrderStatusQuery as OrderStatusQueryContract;
use App\Module\Status\Contracts\Queries\StatusTypeQuery;
use App\Module\Status\Queries\CommentTemplateQuery;
use App\Module\Status\Queries\OrderStatusQuery;
use App\Module\Status\Queries\RefStatusQuery;
use App\Module\Status\Queries\StatusTypeQuery as StatusTypeQueryContract;
use App\Module\Status\Contracts\Queries\RefStatusQuery as RefStatusQueryContract;
use App\Module\Status\Contracts\Queries\CommentTemplateQuery as CommentTemplateQueryContract;
use App\Module\Status\Queries\WaitListStatusQuery;
use Illuminate\Support\ServiceProvider;
use App\Module\Status\Contracts\Queries\WaitListStatusQuery as WaitListStatusQueryContract;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        //order status queries
        OrderStatusQueryContract::class => OrderStatusQuery::class,

        //status type queries
        StatusTypeQuery::class => StatusTypeQueryContract::class,

        //ref status queries
        RefStatusQueryContract::class => RefStatusQuery::class,

        //wait list queries
        WaitListStatusQueryContract::class => WaitListStatusQuery::class,

        //wait list template queries
        CommentTemplateQueryContract::class => CommentTemplateQuery::class,
    ];
}
