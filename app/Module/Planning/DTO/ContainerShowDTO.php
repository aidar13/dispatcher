<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\ContainerShowRequest;

final class ContainerShowDTO
{
    public ?string $invoiceNumber;
    public ?array $deliveryStatusIds;
    public ?int $userId;
    public ?int $courierId;
    public ?string $title;
    public ?int $sectorId;
    public ?int $waveId;
    public ?int $statusId;
    public ?array $statusIds;
    public ?string $date;
    public ?string $dateFrom;
    public ?string $dateTo;
    public ?int $cargoType;
    public ?array $sectorIds;

    public static function fromRequest(ContainerShowRequest $request): self
    {
        $self                    = new self();
        $self->invoiceNumber     = $request->get('invoiceNumber') ?: null;
        $self->deliveryStatusIds = $request->get('deliveryStatusIds') ?: null;
        $self->userId            = (int)$request->get('userId') ?: null;
        $self->courierId         = (int)$request->get('courierId') ?: null;
        $self->title             = $request->get('title') ?: null;
        $self->sectorId          = (int)$request->get('sectorId') ?: null;
        $self->waveId            = (int)$request->get('waveId') ?: null;
        $self->statusId          = (int)$request->get('statusId') ?: null;
        $self->statusIds         = $request->get('statusIds') ?: null;
        $self->date              = $request->get('date');
        $self->dateFrom          = $request->get('dateFrom');
        $self->dateTo            = $request->get('dateTo');
        $self->cargoType         = (int)$request->get('cargoType') ?: null;
        $self->sectorIds         = $request->get('sectorIds');

        return $self;
    }
}
