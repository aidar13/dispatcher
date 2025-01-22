<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

use App\Module\Take\Requests\AssignTakeOrdersToCourierRequest;
use App\Traits\ToArrayTrait;

final class AssignOrderTakeDTO
{
    use ToArrayTrait;

    public int $courierId;
    public array $orderIds;

    public static function fromRequest(AssignTakeOrdersToCourierRequest $request): self
    {
        $self            = new self();
        $self->courierId = (int)$request->get('courierId');
        $self->orderIds  = $request->get('orderIds');

        return $self;
    }
}
