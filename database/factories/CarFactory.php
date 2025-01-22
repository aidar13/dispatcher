<?php

namespace Database\Factories;

use App\Module\Car\Models\Car;
use App\Module\Car\Models\CarType;
use App\Module\Company\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'id'              => $this->faker->unique()->randomNumber(),
            'status_id'       => $this->faker->randomNumber(),
            'company_id'      => Company::factory()->create(),
            'vehicle_type_id' => CarType::factory()->create(),
            'code_1c'         => $this->faker->word,
            'number'          => $this->faker->word,
            'model'           => $this->faker->word,
            'cubature'        => $this->faker->numberBetween(10, 100),
            'created_at'      => Carbon::now()->toDateString(),
        ];
    }
}
