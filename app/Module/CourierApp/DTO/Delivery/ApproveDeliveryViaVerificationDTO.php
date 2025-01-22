<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\Delivery;

use App\Module\CourierApp\Requests\Delivery\ApproveDeliveryViaVerificationRequest;

final class ApproveDeliveryViaVerificationDTO
{
    public string $invoiceNumber;
    public int $verifyType;
    public string $deliveredAt;

    public static function fromRequest(ApproveDeliveryViaVerificationRequest $request): ApproveDeliveryViaVerificationDTO
    {
        $self = new self();

        $self->invoiceNumber = $request->input('invoiceNumber');
        $self->verifyType    = (int)$request->input('verifyType');
        $self->deliveredAt   = $request->input('deliveredAt');

        return $self;
    }
}
