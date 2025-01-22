<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\Integration;

use App\Module\CourierApp\Events\OrderTake\InvoiceCargoSizeTypeSetEvent;
use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\Inventory\Commands\Integration\CreateWriteOffCommand;
use App\Module\Inventory\DTO\Integration\IntegrationWriteOffDTO;
use App\Module\Inventory\Models\Inventory;
use App\Module\Order\Contracts\Queries\InvoiceCargoQuery;

final class CreateWriteOffForSparkDeliveryListener
{
    public function __construct(
        private readonly InvoiceCargoQuery $query,
        private readonly HttpWarehouseQuery $warehouseQuery,
    ) {
    }

    public function handle(InvoiceCargoSizeTypeSetEvent $event): void
    {
        $invoiceCargo = $this->query->getById($event->invoiceCargoId);

        if (!$invoiceCargo) {
            return;
        }

        $warehouse = $this->warehouseQuery->getByCityId($invoiceCargo->invoice->order->sender->city_id);

        if (!$warehouse) {
            return;
        }

        $DTO = new IntegrationWriteOffDTO();
        $DTO->setWarehouseId($warehouse->id);
        $DTO->setWriteOffTypeId(Inventory::WAREHOUSE_WRITE_OFF_TYPE_ID);
        $DTO->setWriteOffItems($invoiceCargo->getWriteOffItemsForSparkDelivery());

        dispatch(new CreateWriteOffCommand($DTO));
    }
}
