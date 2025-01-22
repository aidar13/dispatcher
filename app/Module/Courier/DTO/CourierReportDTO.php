<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO;

use App\Helpers\DateHelper;
use App\Module\Courier\Requests\CourierReportRequest;
use App\Traits\ToArrayTrait;
use Carbon\Carbon;

final class CourierReportDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?string $fromDate;
    public ?string $toDate;
    public ?int $courierId;
    public ?int $dispatcherSectorId;
    public ?int $hasReturn;
    public ?int $hasCash;
    public ?int $hasCodPayment;

    public static function fromRequest(CourierReportRequest $request): self
    {
        $self                     = new self();
        $self->page               = (int)$request->get('page', 1);
        $self->limit              = (int)$request->get('limit', 20);
        $self->courierId          = (int)$request->get('courierId') ?: null;
        $self->dispatcherSectorId = (int)$request->get('dispatcherSectorId') ?: null;
        $self->hasReturn          = (int)$request->get('hasReturn') ?: null;
        $self->hasCash            = (int)$request->get('hasCash') ?: null;
        $self->hasCodPayment      = (int)$request->get('hasCodPayment') ?: null;
        $self->fromDate           = $request->get('fromDate');
        $self->toDate             = $request->get('toDate')
            ? DateHelper::getDate(Carbon::make($request->get('toDate'))->addDay())
            : DateHelper::getDate(now()->addDay());

        return $self;
    }
}
