<?php

declare(strict_types=1);

namespace App\Module\File\DTO\Integration;

use App\Module\File\Models\File;
use App\Traits\ToArrayTrait;

final class IntegrationCreateSavedFileDTO
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

    public static function fromModel(File $file): self
    {
        $self               = new self();
        $self->id           = $file->id;
        $self->path         = $file->path;
        $self->type         = $file->type;
        $self->originalName = $file->original_name;
        $self->clientId     = $file->client_id;
        $self->clientType   = $file->client_type;
        $self->userId       = $file->user_id;
        $self->uuidHash     = $file->uuid_hash;

        return $self;
    }

    public function setClientId(int $clientId): void
    {
        $this->clientId = $clientId;
    }
}
