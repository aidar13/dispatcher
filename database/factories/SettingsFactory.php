<?php

namespace Database\Factories;

use App\Module\Settings\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    protected $model = Settings::class;

    public function definition(): array
    {
        return [
            'key'   => $this->faker->randomElement([Settings::SMS, Settings::PUSH]),
            'value' => 0,
            'label' => $this->faker->title
        ];
    }
}
