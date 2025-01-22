<?php

declare(strict_types=1);

namespace Tests\Repositories;

use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDto;
use App\Module\Gateway\Models\GatewayUser;
use App\ValueObjects\PhoneNumber;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\WithFaker;

final class GatewayUserFakeRepository implements GatewayUserQuery
{
    use WithFaker;

    private string $name;
    private string $email;
    private string $phone;
    private string $password;
    private array $roles;
    private int $id;

    public function __construct(int $id = 1, string $name = '', string $email = '', string $phone = '', string $password = '', array $roles = [])
    {
        $this->setUpFaker();

        $this->name     = $name;
        $this->email    = $email;
        $this->phone    = $phone;
        $this->password = $password;
        $this->roles    = $roles;
        $this->id       = $id;
    }

    public function getUsersWithFilter(GatewayUserDto $dto): ?Collection
    {
        $user        = new GatewayUser();
        $user->name  = $this->name;
        $user->id    = $this->id;
        $user->phone = $this->phone;
        $user->email = $this->faker->email;
        return collect([$user]);
    }

    public function find(int $id): GatewayUser
    {
        $user        = new GatewayUser();
        $user->name  = $this->name;
        $user->id    = $this->id;
        $user->phone = $this->phone;
        $user->email = $this->email;
        $user->roles = $this->roles;
        return $user;
    }

    public function hasPhoneNumber(PhoneNumber $number): bool
    {
        return false;
    }
}
