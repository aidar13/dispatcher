<?php

declare(strict_types=1);

namespace App\Module\Status\DTO\Integration;

use App\Module\Order\Models\Invoice;
use App\Module\Order\Models\Order;
use Illuminate\Support\Carbon;

final class StoreWaitListStatusDTO
{
    public int $id;
    public ?int $parentId;
    public int $code;
    public int $stateId;
    public int $userId;
    public ?string $value;
    public ?string $comment;
    public int $clientId;
    public string $clientType;
    public ?string $source;
    public Carbon $createdAt;

    public static function fromEvent($event): self
    {
        $self             = new self();
        $self->id         = (int)$event->DTO->id;
        $self->parentId   = (int)$event->DTO->parentId ?: null;
        $self->code       = (int)$event->DTO->code;
        $self->stateId    = (int)$event->DTO->stateId;
        $self->userId     = (int)$event->DTO->userId;
        $self->value      = $event->DTO->value;
        $self->comment    = $event->DTO->comment;
        $self->clientId   = $event->DTO->clientId;
        $self->clientType = $self->getClientType($event->DTO->clientType);
        $self->source     = $event->DTO->source;
        $self->createdAt  = new Carbon($event->DTO->createdAt);

        return $self;
    }

    private function getClientType(string $clientType): string
    {
        return match ($clientType) {
            'App\Module\Order\Models\OrderLogisticsInfo' => Invoice::class,
            Order::class                                 => Order::class,
        };
    }
}
