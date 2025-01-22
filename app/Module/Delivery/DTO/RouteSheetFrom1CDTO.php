<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Delivery\Requests\RouteSheetFromOneCRequest;

final class RouteSheetFrom1CDTO
{
    public string $routeSheetNumber;
    public int $courierId;

    public static function fromRequest(RouteSheetFromOneCRequest $request): self
    {
        $self                   = new self();
        $self->routeSheetNumber = $request->get('routeSheetNumber');
        $self->courierId        = (int) $request->get('courierId');

        return $self;
    }
}
