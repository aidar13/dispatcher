<?php

declare(strict_types=1);

namespace App\Module\Gateway\Providers;

use App\Module\Gateway\Contracts\AuthRepository as GatewayAuthRepositoryContract;
use App\Module\Gateway\Contracts\GatewayUserQuery as GatewayUserQueryContract;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Gateway\Contracts\Integration\SendToCabinetRepository;
use App\Module\Gateway\Contracts\TokenQuery as TokenQueryContract;
use App\Module\Gateway\Contracts\TokenRepository as TokenRepositoryContract;
use App\Module\Gateway\Queries\GatewayUserQuery;
use App\Module\Gateway\Queries\TokenQuery;
use App\Module\Gateway\Repositories\Integration\CabinetRepository;
use App\Module\Gateway\Repositories\Integration\Request;
use App\Module\Gateway\Repository\GatewayAuthRepository;
use App\Module\Gateway\Repository\TokenRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        GatewayAuthRepositoryContract::class => GatewayAuthRepository::class,
        TokenQueryContract::class            => TokenQuery::class,
        GatewayUserQueryContract::class      => GatewayUserQuery::class,
        TokenRepositoryContract::class       => TokenRepository::class,
        HttpClientRequest::class             => Request::class,
        SendToCabinetRepository::class       => CabinetRepository::class,
    ];
}
