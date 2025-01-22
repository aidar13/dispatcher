<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\Models\GatewayUser;
use App\Module\User\Commands\UpdateUserCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Repositories\GatewayUserFakeRepository;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testUpdateUser()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'name'  => null,
            'email' => null,
        ]);

        $DTO = new GatewayUser();
        $DTO->name = $this->faker->word;
        $DTO->email = $this->faker->email;

        $this->app->bind(GatewayUserQuery::class, function () use ($DTO) {
            return new GatewayUserFakeRepository(
                name: $DTO->name,
                email: $DTO->email,
            );
        });

        dispatch(new UpdateUserCommand($user->id));

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => $DTO->name,
            'email' => $DTO->email,
        ]);
    }
}
