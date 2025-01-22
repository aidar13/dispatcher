<?php

declare(strict_types=1);

namespace App\Module\Car\Repositories\Eloquent;

use App\Module\Car\Contracts\Repositories\CreateCarRepository;
use App\Module\Car\Contracts\Repositories\UpdateCarRepository;
use App\Module\Car\Models\Car;

final class CarRepository implements CreateCarRepository, UpdateCarRepository
{
    /**
     * @throws \Throwable
     */
    public function create(Car $car): void
    {
        $car->saveOrFail();
    }

    /**
     * @throws \Throwable
     */
    public function update(Car $car): void
    {
        $car->saveOrFail();
    }
}
