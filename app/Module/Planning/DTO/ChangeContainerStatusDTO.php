<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\ChangeContainerStatusRequest;
use Illuminate\Support\Collection;

final class ChangeContainerStatusDTO
{
    public int $containerId;
    public int $containerStatusId;
    public Collection $invoices;

    public static function fromRequest(ChangeContainerStatusRequest $request): self
    {
        $self = new self();

        $self->containerId       = (int)$request->get('containerId');
        $self->containerStatusId = (int)$request->get('containerStatusId');
        $self->invoices          = ContainerInvoicesCollectionDTO::fromArray($request->input('invoices'));

        return $self;
    }
}
