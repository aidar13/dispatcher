<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Module\Order\Requests\SetFastDeliveryCourierRequest;

final class SetFastDeliveryCourierDTO
{
    public ?string $courierName;
    public ?string $courierPhone;
    public ?string $trackingUrl;
    public ?string $internalStatus;
    public ?string $price;

    public static function fromRequest(SetFastDeliveryCourierRequest $request): self
    {
        $self                 = new self();
        $self->courierName    = $request->get('courierName');
        $self->trackingUrl    = $request->get('trackLink');
        $self->courierPhone   = $request->get('courierPhone');
        $self->internalStatus = $request->get('internalStatus');
        $self->price          = $request->get('price');

        return $self;
    }
}
