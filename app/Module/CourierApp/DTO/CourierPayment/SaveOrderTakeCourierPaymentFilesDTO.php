<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\CourierPayment;

use App\Module\CourierApp\Requests\CourierPayment\SaveCourierPaymentFilesRequest;
use App\Module\Order\Models\Order;

final class SaveOrderTakeCourierPaymentFilesDTO
{
    public int $clientId;
    public string $clientType;
    public int $cost;
    public int $type;
    public array $checks;

    public static function fromRequest(SaveCourierPaymentFilesRequest $request): self
    {
        $self             = new self();
        $self->clientId   = (int)$request->get('clientId');
        $self->clientType = Order::class;
        $self->cost       = (int)$request->get('cost');
        $self->type       = (int)$request->get('type');
        $self->checks     = $request->file('checks');

        return $self;
    }
}
