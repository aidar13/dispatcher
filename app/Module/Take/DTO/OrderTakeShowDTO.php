<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

use App\Module\Take\Requests\OrderTakeShowRequest;
use App\Traits\ToArrayTrait;

final class OrderTakeShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?int $dispatcherSectorId;
    public ?int $courierId;
    public ?int $companyId;
    public ?string $address;
    public ?int $cityId;
    public ?string $orderNumber;
    public ?int $periodId;
    public ?string $dateFrom;
    public ?string $dateTo;
    public ?array $statusIds;
    public ?array $notInStatusIds;
    public ?array $waitListStatusIds;
    public ?string $createdAtFrom;
    public ?string $createdAtTo;
    public ?bool $hasPackType;
    public ?string $waitListComment;
    public ?bool $incompletedAllTime;

    public static function fromRequest(OrderTakeShowRequest $request): self
    {
        $self                     = new self();
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->courierId          = (int)$request->get('courierId') ?: null;
        $self->companyId          = (int)$request->get('companyId') ?: null;
        $self->address            = $request->get('address');
        $self->cityId             = (int)$request->get('cityId') ?: null;
        $self->orderNumber        = $request->get('orderNumber');
        $self->periodId           = (int)$request->get('periodId') ?: null;
        $self->dateFrom           = $request->get('dateFrom');
        $self->dateTo             = $request->get('dateTo');
        $self->statusIds          = $request->get('statusIds');
        $self->notInStatusIds     = $request->get('notInStatusIds');
        $self->waitListStatusIds  = $request->get('waitListStatusIds');
        $self->createdAtFrom      = $request->get('createdAtFrom');
        $self->createdAtTo        = $request->get('createdAtTo');
        $self->hasPackType        = $request->has('hasPackType') ? (bool)$request->get('hasPackType') : null;
        $self->incompletedAllTime = (bool)$request->get('incompletedAllTime');
        $self->page               = (int)$request->get('page', 1);
        $self->limit              = (int)$request->get('limit', 20);
        $self->waitListComment    = $request->get('waitListComment');

        return $self;
    }
}
