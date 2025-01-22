<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

use App\Module\Take\Requests\SetStatusToTakeByInvoiceRequest;

final class SetStatusToTakeByInvoiceDTO
{
    public int $invoiceId;
    public int $statusId;

    public static function fromRequest(SetStatusToTakeByInvoiceRequest $request): self
    {
        $self            = new self();
        $self->invoiceId = (int)$request->get('invoiceId');
        $self->statusId  = (int)$request->get('statusId');

        return $self;
    }
}
