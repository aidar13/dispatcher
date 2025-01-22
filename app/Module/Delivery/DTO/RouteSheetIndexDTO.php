<?php

declare(strict_types=1);

namespace App\Module\Delivery\DTO;

use App\Module\Delivery\Requests\RouteSheetIndexRequest;
use App\Traits\ToArrayTrait;

final class RouteSheetIndexDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?int $courierId;
    public ?int $cityId;
    public ?int $sectorId;
    public ?int $waveId;
    public ?int $dispatcherSectorId;
    public ?string $invoiceNumber;
    public ?string $number;
    public ?string $fromDate;
    public ?string $toDate;

    public static function fromRequest(RouteSheetIndexRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->courierId          = (int)$request->get('courierId') ?: null;
        $self->cityId             = (int)$request->get('cityId') ?: null;
        $self->sectorId           = (int)$request->get('sectorId') ?: null;
        $self->waveId             = (int)$request->get('waveId') ?: null;
        $self->invoiceNumber      = $request->get('invoiceNumber');
        $self->number             = $request->get('number');
        $self->fromDate           = $request->get('fromDate');
        $self->toDate             = $request->get('toDate');
        $self->page               = (int)$request->get('page', 1);
        $self->limit              = (int)$request->get('limit', 20);

        return $self;
    }
}
