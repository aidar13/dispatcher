<?php

declare(strict_types=1);

namespace App\Module\City\Providers;

use App\Module\City\Contracts\Repositories\CreateCityRepository;
use App\Module\City\Contracts\Repositories\CreateCountryRepository;
use App\Module\City\Contracts\Repositories\CreateRegionRepository;
use App\Module\City\Contracts\Repositories\UpdateCityRepository;
use App\Module\City\Repositories\Eloquent\CityRepository;
use App\Module\City\Repositories\Eloquent\CountryRepository;
use App\Module\City\Repositories\Eloquent\RegionRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        CreateCityRepository::class    => CityRepository::class,
        UpdateCityRepository::class    => CityRepository::class,
        CreateRegionRepository::class  => RegionRepository::class,
        CreateCountryRepository::class => CountryRepository::class,
    ];
}
