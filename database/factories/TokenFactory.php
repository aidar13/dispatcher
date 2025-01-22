<?php

namespace Database\Factories;

use App\Module\Gateway\Model\Token;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Token::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'adapter'       => $this->faker->word,
            'access_token'  => Str::uuid()->toString(),
            'refresh_token' => Str::uuid()->toString(),
            'scope'         => $this->faker->word,
            'expires_in'    => now()->addMinutes(10),
        ];
    }
}
