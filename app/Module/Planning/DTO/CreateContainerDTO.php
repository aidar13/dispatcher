<?php

declare(strict_types=1);

namespace App\Module\Planning\DTO;

use App\Module\Planning\Requests\CreateContainerRequest;
use Illuminate\Support\Collection;

final class CreateContainerDTO
{
    public int $waveId;
    public string $date;
    public int $sectorId;
    public int $cargoType;
    public int|null $courierId;
    public int|null $routingId;
    public Collection $invoiceIds;

    public function __construct()
    {
        $this->courierId  = null;
        $this->routingId  = null;
        $this->invoiceIds = collect();
    }

    public static function fromRequest(CreateContainerRequest $request): self
    {
        $self             = new self();
        $self->waveId     = (int)$request->get('waveId');
        $self->sectorId   = (int)$request->get('sectorId');
        $self->cargoType  = (int)$request->get('cargoType');
        $self->date       = $request->get('date');
        $self->invoiceIds = collect($request->get('invoiceIds', []));

        return $self;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function setWaveId(int $waveId): void
    {
        $this->waveId = $waveId;
    }

    public function setSectorId(int $sectorId): void
    {
        $this->sectorId = $sectorId;
    }

    public function setCargoType(int $cargoType): void
    {
        $this->cargoType = $cargoType;
    }

    public function setCourierId(int $courierId): void
    {
        $this->courierId = $courierId;
    }

    public function setRoutingId(int $routingId): void
    {
        $this->routingId = $routingId;
    }

    public function setInvoiceIds(Collection $invoiceIds): void
    {
        $this->invoiceIds = $invoiceIds;
    }
}
