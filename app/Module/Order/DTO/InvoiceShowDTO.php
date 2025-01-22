<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Module\Order\Requests\InvoiceShowRequest;

final class InvoiceShowDTO
{
    public int $dispatcherSectorId;
    public string $date;
    public ?int $waitListStatus = null;
    public ?string $invoiceNumber;

    public static function fromRequest(InvoiceShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId');
        $self->date               = $request->get('date');
        $self->invoiceNumber      = $request->get('invoiceNumber');

        return $self;
    }

    public function setWaitListStatus(int $waitListStatus): void
    {
        $this->waitListStatus = $waitListStatus;
    }
}
