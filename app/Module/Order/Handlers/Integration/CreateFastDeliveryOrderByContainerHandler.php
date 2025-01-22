<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers\Integration;

use App\Exceptions\DomainExceptionWithErrors;
use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\DispatcherSector\Exceptions\WarehouseNotFoundException;
use App\Module\Order\Commands\CreateFastDeliveryOrderByContainerCommand;
use App\Module\Order\Contracts\Repositories\Integration\CreateFastDeliveryOrderRepository;
use App\Module\Order\DTO\Integration\CreateFastDeliveryOrderDTO;
use App\Module\Order\Models\FastDeliveryOrder;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Events\FastDeliveryOrderCreatedByContainerEvent;
use App\Module\Planning\Models\ContainerStatus;
use Illuminate\Support\Facades\Log;

final class CreateFastDeliveryOrderByContainerHandler
{
    public function __construct(
        private readonly ContainerQuery $containerQuery,
        private readonly CreateFastDeliveryOrderRepository $orderRepository,
        private readonly HttpWarehouseQuery $warehouseQuery
    ) {
    }

    /**
     * @throws WarehouseNotFoundException
     * @throws DomainExceptionWithErrors
     */
    public function handle(CreateFastDeliveryOrderByContainerCommand $command): void
    {
        $container = $this->containerQuery->getById(
            $command->containerId,
            ['*'],
            ['invoices', 'invoices.receiver', 'sector', 'sector.dispatcherSector']
        );

        if (
            $container->fastDeliveryOrder?->type === FastDeliveryOrder::TYPE_YANDEX_DELIVERY &&
            $container->status_id !== ContainerStatus::ID_ASSEMBLED
        ) {
            return;
        }

        if ($container->fastDeliveryOrder?->internal_id !== null) {
            return;
        }

        $cityId    = $container->sector->dispatcherSector->city_id;
        $warehouse = $this->warehouseQuery->getByCityId($cityId);

        if (!$warehouse) {
            throw new WarehouseNotFoundException("Склад по городу $cityId не найден");
        }

        Log::info("Создание заказа быстрой доставки по контейнеру $container->id");

        if (!$container->isReadyToCreateFastDelivery()) {
            throw new DomainExceptionWithErrors('Контейнер еще не готов!');
        }

        $fastDeliveryOrderDTO = $this->orderRepository->create(CreateFastDeliveryOrderDTO::fromContainerAndWarehouse($container, $warehouse));

        if (!$fastDeliveryOrderDTO->internalOrderId) {
            Log::info("По контейнеру $container->id не записалась быстрая доставка");
        }

        event(new FastDeliveryOrderCreatedByContainerEvent($container->id, $fastDeliveryOrderDTO));
    }
}
