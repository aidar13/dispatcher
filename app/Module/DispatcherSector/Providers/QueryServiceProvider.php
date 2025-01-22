<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Providers;

use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\DispatcherSectorQuery as DispatcherSectorQueryContract;
use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery as DispatchersSectorUserQueryContract;
use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorPolygonQuery;
use App\Module\DispatcherSector\Contracts\Queries\SectorQuery as SectorQueryContract;
use App\Module\DispatcherSector\Contracts\Queries\WaveQuery as WaveQueryContract;
use App\Module\DispatcherSector\Queries\Cache\DispatcherSectorQuery as DispatcherSectorCacheQuery;
use App\Module\DispatcherSector\Queries\Eloquent\DispatcherSectorQuery;
use App\Module\DispatcherSector\Queries\Eloquent\DispatcherSectorUserQuery;
use App\Module\DispatcherSector\Queries\Eloquent\SectorQuery;
use App\Module\DispatcherSector\Queries\Eloquent\WaveQuery;
use App\Module\DispatcherSector\Queries\Http\WarehouseQuery;
use Illuminate\Support\ServiceProvider;

final class QueryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        DispatcherSectorQueryContract::class      => DispatcherSectorCacheQuery::class,
        DispatcherSectorPolygonQuery::class       => DispatcherSectorQuery::class,
        DispatchersSectorUserQueryContract::class => DispatcherSectorUserQuery::class,
        SectorQueryContract::class                => SectorQuery::class,
        SectorPolygonQuery::class                 => SectorQuery::class,
        WaveQueryContract::class                  => WaveQuery::class,
        HttpWarehouseQuery::class                 => WarehouseQuery::class,

    ];

    public function register(): void
    {
        $this->app->when(DispatcherSectorCacheQuery::class)
            ->needs(DispatcherSectorQueryContract::class)
            ->give(DispatcherSectorQuery::class);
    }
}
