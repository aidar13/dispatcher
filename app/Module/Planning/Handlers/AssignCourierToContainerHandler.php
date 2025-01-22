<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Exceptions\DomainExceptionWithErrors;
use App\Module\Order\Commands\CreateFastDeliveryOrderCommand;
use App\Module\Order\Models\FastDeliveryOrder;
use App\Module\Planning\Commands\AssignCourierToContainerCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\UpdateContainerRepository;
use App\Module\Planning\Models\Container;
use App\Module\Planning\Models\ContainerStatus;
use Illuminate\Support\Facades\Log;

final class AssignCourierToContainerHandler
{
    public function __construct(
        private readonly ContainerQuery $containerQuery,
        private readonly UpdateContainerRepository $containerRepository,
    ) {
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    public function handle(AssignCourierToContainerCommand $command): void
    {
        $container = $this->containerQuery->getById($command->containerId);

        if ($command->providerId === FastDeliveryOrder::TYPE_YANDEX_DELIVERY) {
            $this->checkWeight($container);
        }

        $container->courier_id = $this->getCourierId($container, $command);
        $container->status_id  = $command->providerId ? ContainerStatus::ID_FAST_DELIVERY_SELECTED : ContainerStatus::ID_COURIER_APPOINTED;

        $this->containerRepository->update($container);

        if (!is_null($command->providerId)) {
            dispatch(new CreateFastDeliveryOrderCommand($container->id, null, $command->providerId));
        }
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    private function getCourierId(Container $container, AssignCourierToContainerCommand $command): int
    {
        if (is_null($command->providerId)) {
            return (int)$command->courierId;
        }

        $courierId = $container->sector?->dispatcherSector->courier_id;

        if (!$courierId) {
            Log::info(__('Для контейнера :containerId не найден курьер'));
            throw new DomainExceptionWithErrors('Произошла ошибка');
        }

        return $courierId;
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    private function checkWeight(Container $container): void
    {
        $weight = 0;

        foreach ($container->invoices as $invoice) {
            $weight += (float)$invoice->cargo?->weight;
        }

        $maxWeight = FastDeliveryOrder::YANDEX_MAX_WEIGHT;
        if ($weight > $maxWeight) {
            throw new DomainExceptionWithErrors("Вес груза не должен превышает $maxWeight кг");
        }
    }
}
