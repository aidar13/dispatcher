<?php

namespace Database\Factories;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierLicense;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourierLicense>
 */
class CourierLicenseFactory extends Factory
{
    protected $model = CourierLicense::class;

    public function definition(): array
    {
        return [
            'courier_id'                => Courier::factory()->create(),
            'identify_card_number'      => $this->faker->word(),
            'identify_card_issue_date'  => $this->faker->date(),
            'driver_license_number'     => $this->faker->word(),
            'driver_license_issue_date' => $this->faker->date(),
        ];
    }
}
