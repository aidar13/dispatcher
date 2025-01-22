<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\Delivery;

use App\Module\CourierApp\Requests\Delivery\ApproveDeliveryRequest;

final class ApproveDeliveryDTO
{
    public string $deliveryReceiverName;
    public array $attachments;
    public string $deliveredAt;

    public static function fromRequest(ApproveDeliveryRequest $request): ApproveDeliveryDTO
    {
        $self = new self();

        $self->deliveryReceiverName = $request->input('deliveryReceiverName');
        $self->attachments          = $request->file('attachments');
        $self->deliveredAt          = $request->input('deliveredAt');

        return $self;
    }
}
