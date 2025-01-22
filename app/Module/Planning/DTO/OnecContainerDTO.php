<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use Illuminate\Support\Arr;

final class OnecContainerDTO
{
    public bool $success;
    public ?string $error;
    public ?string $docNumber;
    public ?int $containerId;

    public static function fromArray(array $item): self
    {
        $self = new self();

        $self->success     = Arr::get($item, 'success', false);
        $self->error       = Arr::get($item, 'error');
        $self->docNumber   = Arr::get($item, 'doc_number');
        $self->containerId = (int)Arr::get($item, 'container_id') ?: null;

        return $self;
    }
}
