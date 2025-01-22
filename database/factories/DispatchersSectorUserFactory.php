<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Module\DispatcherSector\Models\DispatcherSector;
use App\Module\DispatcherSector\Models\DispatchersSectorUser;
use Illuminate\Database\Eloquent\Factories\Factory;

final class DispatchersSectorUserFactory extends Factory
{
    protected $model = DispatchersSectorUser::class;

    public function definition(): array
    {
        return [
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'user_id'              => User::factory()->create(['id' => $this->faker->numberBetween(10, 100)])
        ];
    }
}

