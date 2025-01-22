<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\AssignCourierToContainerRequest;

final class AssignCourierToContainerDTO
{
    public ?int $courierId;
    public array $containerIds;
    public bool $isFastDelivery;
    public ?int $providerId;

    public static function fromRequest(AssignCourierToContainerRequest $request): self
    {
        $self                 = new self();
        $self->courierId      = (int)$request->get('courierId') ?: null;
        $self->containerIds   = $request->get('containerIds', []);
        $self->isFastDelivery = (bool)$request->get('isFastDelivery', false);
        $self->providerId     = (int)$request->get('provider_id') ?: null;

        return $self;
    }
}
