<?php

namespace Database\Factories;

use App\Models\User;
use App\Module\File\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition(): array
    {
        return [
            'type'          => $this->faker->randomNumber(),
            'path'          => $this->faker->word,
            'original_name' => $this->faker->word,
            'client_id'     => $this->faker->randomNumber(),
            'client_type'   => $this->faker->word,
            'user_id'       => User::factory()->create(),
            'uuid_hash'     => $this->faker->uuid,
        ];
    }
}
