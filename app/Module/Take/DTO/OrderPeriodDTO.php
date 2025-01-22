<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

use App\Module\Take\Requests\OrderPeriodRequest;

final class OrderPeriodDTO
{
    public int $limit;
    public int $page;

    public static function fromRequest(OrderPeriodRequest $request): self
    {
        $self        = new self();
        $self->page  = (int)$request->get('page', 1);
        $self->limit = (int)$request->get('limit', 20);

        return $self;
    }
}
