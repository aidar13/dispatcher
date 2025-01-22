<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\Delivery;

use App\Module\CourierApp\Requests\Delivery\CourierDeliveryShowRequest;
use App\Traits\ToArrayTrait;
use Illuminate\Support\Facades\Auth;

final class CourierDeliveryShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?string $search;
    public ?string $createdAtFrom;
    public ?string $createdAtTo;
    public ?string $deliveredAtFrom;
    public ?string $deliveredAtTo;
    public ?string $deliveryDateFrom;
    public ?string $deliveryDateTo;
    public ?int $userId;
    public ?array $statusIds;
    public ?array $notInStatusIds;

    public static function fromRequest(CourierDeliveryShowRequest $request): self
    {
        $self                   = new self();
        $self->page             = (int)$request->get('page', 1);
        $self->limit            = (int)$request->get('limit', 20);
        $self->search           = $request->get('search');
        $self->createdAtFrom    = $request->get('createdAtFrom');
        $self->createdAtTo      = $request->get('createdAtTo');
        $self->userId           = (int)Auth::id() ?: null;
        $self->statusIds        = $request->get('statusIds');
        $self->notInStatusIds   = $request->get('notInStatusIds');
        $self->deliveredAtFrom  = $request->get('deliveredAtFrom');
        $self->deliveredAtTo    = $request->get('deliveredAtTo');
        $self->deliveryDateFrom = $request->get('deliveryDateFrom');
        $self->deliveryDateTo   = $request->get('deliveryDateTo');

        return $self;
    }
}
