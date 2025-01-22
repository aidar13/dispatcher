<?php

declare(strict_types=1);

namespace App\Module\Monitoring\DTO;

use App\Helpers\DateHelper;
use App\Module\Monitoring\Requests\DeliveryInfoShowRequest;
use App\Traits\ToArrayTrait;

final class DeliveryInfoShowDTO
{
    use ToArrayTrait;

    public ?int $dispatcherSectorId;
    public ?string $createdAtFrom;
    public ?string $createdAtTo;

    public static function fromRequest(DeliveryInfoShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->createdAtFrom      = $request->get('createdAtFrom') ?? DateHelper::getDate(now());
        $self->createdAtTo        = $request->get('createdAtTo') ?? DateHelper::getDate(now());

        return $self;
    }
}
