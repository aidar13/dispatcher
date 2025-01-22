<?php

declare(strict_types=1);

namespace App\Module\Car\Providers;

use App\Module\Car\Contracts\Repositories\CreateCarOccupancyRepository;
use App\Module\Car\Contracts\Repositories\CreateCarRepository;
use App\Module\Car\Contracts\Repositories\UpdateCarRepository;
use App\Module\Car\Repositories\Eloquent\CarOccupancyRepository;
use App\Module\Car\Repositories\Eloquent\CarRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateCarRepository::class          => CarRepository::class,
        UpdateCarRepository::class          => CarRepository::class,
        CreateCarOccupancyRepository::class => CarOccupancyRepository::class
    ];
}
