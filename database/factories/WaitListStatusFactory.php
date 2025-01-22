<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\WaitListStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

final class WaitListStatusFactory extends Factory
{
    protected $model = WaitListStatus::class;

    /**
     * @psalm-suppress UndefinedMagicPropertyFetch
     */
    public function definition(): array
    {
        return [
            'client_id'   => Invoice::factory()->create(),
            'client_type' => Invoice::class,
            'code'        => RefStatus::factory()->create()->code,
            'value'       => $this->faker->word,
            'comment'     => $this->faker->word,
            'state_id'    => $this->faker->randomElement([WaitListStatus::ID_IN_WORK_AT_CC, WaitListStatus::ID_CONFIRMED, WaitListStatus::ID_DENIED]),
            'user_id'     => User::factory()->create()
        ];
    }
}
