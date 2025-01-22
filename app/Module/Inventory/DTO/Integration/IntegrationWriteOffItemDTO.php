<?php

declare(strict_types=1);

namespace App\Module\Inventory\DTO\Integration;

final class IntegrationWriteOffItemDTO
{
    public int $inventoryItemId;
    public float $amount;

    public static function fromArray(array $writeOffItem): self
    {
        $self = new self();

        $self->inventoryItemId = $writeOffItem['inventoryItemId'];
        $self->amount          = $writeOffItem['amount'];

        return $self;
    }

    public function toArray(): array
    {
        return [
            'inventoryItemId' => $this->inventoryItemId,
            'amount'          => $this->amount,
        ];
    }
}
