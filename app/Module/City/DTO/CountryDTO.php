<?php

declare(strict_types=1);

namespace App\Module\City\DTO;

final class CountryDTO
{
    public int $id;
    public string $name;
    public string $title;

    public static function fromEvent($event): self
    {
        $self        = new self();
        $self->id    = $event->DTO->id;
        $self->name  = $event->DTO->name;
        $self->title = $event->DTO->title;

        return $self;
    }
}
