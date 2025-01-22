<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Delivery\Requests\DeliveriesReportRequest;
use App\Traits\ToArrayTrait;

final class DeliveryReportDTO
{
    use ToArrayTrait;

    public ?int $dispatcherSectorId;
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

    public static function fromRequest(DeliveriesReportRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
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
        $self->waitListComment    = $request->get('waitListComment');

        return $self;
    }
}
