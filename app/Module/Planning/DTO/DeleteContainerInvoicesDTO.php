<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\DeleteContainerInvoicesRequest;

final class DeleteContainerInvoicesDTO
{
    public int $containerId;
    public array $invoiceIds;

    public static function fromRequest(DeleteContainerInvoicesRequest $request): self
    {
        $self = new self();

        $self->containerId = (int)$request->get('containerId');
        $self->invoiceIds  = $request->get('invoiceIds', []);

        return $self;
    }
}
