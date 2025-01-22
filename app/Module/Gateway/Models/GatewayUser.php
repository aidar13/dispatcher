<?php

declare(strict_types=1);

namespace App\Module\Gateway\Models;

use App\Module\Gateway\Exceptions\GatewayNotFoundException;
use App\Traits\ToArrayTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class GatewayUser
{
    use ToArrayTrait;

    public int $id;
    public ?string $email;
    public ?array $permissions;
    public ?array $roles;
    public ?string $name;
    public ?string $phone;

    public static function fromArray(array $data): self
    {
        $self = new self();

        if (!isset($data['data'])) {
            throw new GatewayNotFoundException("В Gateway нету такого пользователя");
        }

        if (!Arr::isAssoc($data['data'])) {
            $data['data'] = current($data['data']);
        }

        $self->id          = $data['data']['id'];
        $self->email       = $data['data']['email'];
        $self->name        = $data['data']['name'];
        $self->phone       = $data['data']['phone'];
        $self->permissions = $data['data']['permissions'];
        $self->roles       = $data['data']['roles'];

        return $self;
    }

    public static function fromCollection(Collection $collection): self
    {
        $self = new self();

        if ($collection->isEmpty()) {
            throw new GatewayNotFoundException("В Gateway нету такого пользователя");
        }

        $self->id    = $collection->first()['id'];
        $self->email = $collection->first()['email'];
        $self->name  = $collection->first()['name'];
        $self->phone = $collection->first()['phone'];

        return $self;
    }

    public static function fromArrayOfObjects(array $data): self
    {
        $self = new self();

        $self->id    = $data['id'];
        $self->email = $data['email'] ?? null;
        $self->name  = $data['name'] ?? null;
        $self->phone = $data['phone'] ?? null;
        $self->roles = $data['roles'] ?? null;

        return $self;
    }
}
