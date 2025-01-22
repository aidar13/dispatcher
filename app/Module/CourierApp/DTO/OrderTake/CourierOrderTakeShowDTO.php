<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\OrderTake;

use App\Module\CourierApp\Requests\OrderTake\CourierOrderTakeShowRequest;
use App\Traits\ToArrayTrait;
use Illuminate\Support\Facades\Auth;

final class CourierOrderTakeShowDTO
{
    use ToArrayTrait;

    public int $limit;
    public int $page;
    public ?string $search;
    public ?string $dateFrom;
    public ?string $dateTo;
    public ?string $takenAtFrom;
    public ?string $takenAtTo;
    public ?int $userId;
    public ?array $statusIds;
    public ?array $notInStatusIds;

    public static function fromRequest(CourierOrderTakeShowRequest $request): self
    {
        $self                 = new self();
        $self->page           = (int)$request->get('page', 1);
        $self->limit          = (int)$request->get('limit', 20);
        $self->search         = $request->get('search');
        $self->dateFrom       = $request->get('dateFrom');
        $self->dateTo         = $request->get('dateTo');
        $self->takenAtFrom    = $request->get('takenAtFrom');
        $self->takenAtTo      = $request->get('takenAtTo');
        $self->userId         = (int)Auth::id() ?: null;
        $self->statusIds      = $request->get('statusIds');
        $self->notInStatusIds = $request->get('notInStatusIds');

        return $self;
    }
}
