<?php

declare(strict_types=1);

namespace App\Module\Status\Providers;

use App\Module\Status\Contracts\Services\StatusTypeService;
use App\Module\Status\Contracts\Services\TakeStatusService as TakeStatusServiceContract;
use App\Module\Status\Services\CommentTemplateService;
use App\Module\Status\Services\StatusTypeService as StatusTypeServiceContract;
use App\Module\Status\Contracts\Services\CommentTemplateService as CommentTemplateServiceContract;
use App\Module\Status\Services\TakeStatusService;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        StatusTypeService::class              => StatusTypeServiceContract::class,
        CommentTemplateServiceContract::class => CommentTemplateService::class,
        TakeStatusServiceContract::class      => TakeStatusService::class,
    ];
}
