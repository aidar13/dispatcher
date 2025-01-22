<?php

declare(strict_types=1);

namespace App\Module\Car\Contracts\Repositories;

use App\Module\Car\Models\Car;

interface CreateCarRepository
{
    public function create(Car $car): void;
}
