<?php

declare(strict_types=1);

namespace App\Module\File\DTO\Integration;

use App\Traits\ToArrayTrait;

final class IntegrationFileDTO
{
    use ToArrayTrait;

    public int $id;
    public string $path;
    public int $type;
    public string $originalName;
    public int $clientId;
    public string $clientType;
    public int $userId;
    public string $uuidHash;

    public static function fromEvent($event): self
    {
        $self               = new self();
        $self->id           = $event->id;
        $self->path         = $event->path;
        $self->type         = $event->type;
        $self->originalName = $event->originalName;
        $self->clientId     = $event->clientId;
        $self->clientType   = $event->clientType;
        $self->userId       = $event->userId;
        $self->uuidHash     = $event->uuidHash;

        return $self;
    }
}
