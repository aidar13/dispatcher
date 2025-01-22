<?php

declare(strict_types=1);

namespace App\Module\Inventory\DTO\Integration;

final class IntegrationWriteOffDTO
{
    public int $writeOffTypeId;
    public int $warehouseId;
    public array $writeOffItems;

    public function setWriteOffTypeId(int $writeOffTypeId): void
    {
        $this->writeOffTypeId = $writeOffTypeId;
    }

    public function setWarehouseId(int $warehouseId): void
    {
        $this->warehouseId = $warehouseId;
    }

    public function setWriteOffItems(array $writeOffItems): void
    {
        $this->writeOffItems = $writeOffItems;
    }

    public function toArray(): array
    {
        return [
            'warehouseId'    => $this->warehouseId,
            'writeOffTypeId' => $this->writeOffTypeId,
            'writeOffItems'  => IntegrationWriteOffItemCollectionDTO::fromArray($this->writeOffItems),
        ];
    }
}
