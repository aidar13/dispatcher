<?php

namespace Database\Factories;

use App\Models\User;
use App\Module\Car\Models\Car;
use App\Module\Company\Models\Company;
use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierScheduleType;
use App\Module\Courier\Models\CourierStatus;
use App\Module\DispatcherSector\Models\DispatcherSector;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CourierFactory extends Factory
{
    protected $model = Courier::class;

    public function definition(): array
    {
        return [
            'id'                   => $this->faker->unique()->randomNumber(),
            'user_id'              => User::factory()->create(),
            'car_id'               => Car::factory()->create(),
            'company_id'           => Company::factory()->create(),
            'full_name'            => $this->faker->name,
            'phone_number'         => $this->faker->numerify('7707#######'),
            'dispatcher_sector_id' => DispatcherSector::factory()->create(),
            'code_1c'              => $this->faker->word,
            'status_id'            => CourierStatus::ID_IN_CHECKUP,
            'iin'                  => $this->faker->numerify('############'),
            'is_active'            => $this->faker->boolean,
            'payment_rate_type'    => $this->faker->randomNumber(),
            'payment_amount'       => $this->faker->randomFloat(2),
            'schedule_type_id'     => CourierScheduleType::factory()->create(),
            'created_at'           => Carbon::now()->toDateString(),
        ];
    }
}
