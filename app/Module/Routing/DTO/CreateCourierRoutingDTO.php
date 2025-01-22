<?php

declare(strict_types=1);

namespace App\Module\Routing\DTO;

use App\Module\Routing\Requests\CreateCourierRoutingRequest;
use Illuminate\Support\Facades\Auth;

final class CreateCourierRoutingDTO
{
    public ?int $userId = null;
    public int $courierId;

    public static function fromRequest(CreateCourierRoutingRequest $request): self
    {
        $self            = new self();
        $self->courierId = (int)$request->get('courierId');
        $self->userId    = (int)Auth::id();

        return $self;
    }
}
