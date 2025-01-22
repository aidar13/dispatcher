<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\DTO;

use Illuminate\Support\Arr;

final class WarehouseDTO
{
    public ?int $id;
    public ?string $title;
    public ?int $cityId;
    public ?string $street;
    public ?string $house;
    public ?string $office;
    public ?string $index;
    public ?string $fullAddress;
    public ?string $latitude;
    public ?string $longitude;
    public ?string $fullName;
    public ?string $phone;

    public static function fromArray(array $data): self
    {
        $self              = new self();
        $self->id          = (int)Arr::get($data, 'id') ?: null;
        $self->title       = Arr::get($data, 'title');
        $self->cityId      = (int)Arr::get($data, 'city_id') ?: null;
        $self->street      = Arr::get($data, 'street');
        $self->house       = Arr::get($data, 'house');
        $self->office      = Arr::get($data, 'office');
        $self->index       = Arr::get($data, 'index');
        $self->fullAddress = Arr::get($data, 'full_address');
        $self->latitude    = Arr::get($data, 'latitude');
        $self->longitude   = Arr::get($data, 'longitude');
        $self->fullName    = Arr::get($data, 'full_name');
        $self->phone       = Arr::get($data, 'phone');

        return $self;
    }
}
