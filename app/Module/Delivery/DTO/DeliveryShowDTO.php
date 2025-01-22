<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Delivery\Requests\DeliveriesShowRequest;
use App\Traits\ToArrayTrait;

final class DeliveryShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?int $dispatcherSectorId;
    public ?int $containerId;
    public ?array $statusIds;
    public ?array $notInStatusIds;
    public ?array $waitListStatusIds;
    public ?string $invoiceNumber;
    public ?string $address;
    public ?int $companyId;
    public ?int $courierId;
    public ?int $sectorId;
    public ?string $createdAtFrom;
    public ?string $createdAtTo;
    public ?string $waitListComment;

    public static function fromRequest(DeliveriesShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->containerId        = (int)$request->get('containerId') ?: null;
        $self->statusIds          = $request->get('statusIds');
        $self->notInStatusIds     = $request->get('notInStatusIds');
        $self->waitListStatusIds  = $request->get('waitListStatusIds');
        $self->invoiceNumber      = $request->get('invoiceNumber');
        $self->address            = $request->get('address');
        $self->companyId          = (int)$request->get('companyId') ?: null;
        $self->courierId          = (int)$request->get('courierId') ?: null;
        $self->sectorId           = (int)$request->get('sectorId') ?: null;
        $self->createdAtFrom      = $request->get('createdAtFrom');
        $self->createdAtTo        = $request->get('createdAtTo');
        $self->page               = (int)$request->get('page', 1);
        $self->limit              = (int)$request->get('limit', 20);
        $self->waitListComment    = $request->get('waitListComment');

        return $self;
    }
}
