<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\OrderTake;

use App\Module\CourierApp\Requests\OrderTake\MassApproveOrderTakeRequest;

final class MassApproveOrderTakeDTO
{
    public int $orderId;
    public array $invoices;
    public array $places;

    public static function fromRequest(MassApproveOrderTakeRequest $request): self
    {
        $self           = new self();
        $self->orderId  = (int)$request->input('orderId');
        $self->invoices = (array)$request->input('invoices');

        return $self;
    }
}
