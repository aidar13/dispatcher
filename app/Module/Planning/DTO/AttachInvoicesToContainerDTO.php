<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\AttachInvoicesToContainerRequest;

final class AttachInvoicesToContainerDTO
{
    public int $containerId;
    public array $invoiceIds;

    public static function fromRequest(AttachInvoicesToContainerRequest $request): self
    {
        $self = new self();

        $self->containerId = (int)$request->containerId;
        $self->invoiceIds  = $request->get('invoiceIds', []);

        return $self;
    }
}
