<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Models\User;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\DispatcherSector\Models\Wave;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use App\Module\Planning\Commands\CreateContainerCommand;
use App\Module\Planning\Commands\CreateContainerFromRoutingCommand;
use App\Module\Planning\DTO\CreateContainerDTO;
use App\Module\Planning\Models\Container;
use App\Module\Routing\Contracts\Queries\RoutingQuery;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository;
use App\Module\Routing\DTO\IntegrationRoutingItemDTO;

final readonly class CreateContainerFromRoutingHandler
{
    public function __construct(
        private RoutingQuery $routingQuery,
        private IntegrationRoutingRepository $routingRepository,
    ) {
    }

    public function handle(CreateContainerFromRoutingCommand $command): void
    {
        $routing = $this->routingQuery->getById($command->routingId);

        if ($routing->containers->isNotEmpty()) {
            return;
        }

        $DTO = $this->routingRepository->getByTaskId($routing->task_id);
        /** @var Wave $wave */
        $wave = $routing->dispatcherSector->waves()
            ->orderBy('from_time')
            ->first();

        /** @var IntegrationRoutingItemDTO $route */
        foreach ($DTO->routes as $route) {
            $invoiceIds = $this->getInvoices($route);

            $dto = new CreateContainerDTO();
            $dto->setInvoiceIds(collect($invoiceIds));
            $dto->setDate(now()->format('Y-m-d'));
            $dto->setCargoType(Container::SMALL_CARGO_ID);
            $dto->setSectorId($this->getSectorId($invoiceIds, $routing->dispatcherSector->default_sector_id));
            $dto->setWaveId($wave->id);
            $dto->setCourierId($this->getCourierId($route->carNumber));
            $dto->setRoutingId($routing->id);

            dispatch(new CreateContainerCommand(
                User::USER_ADMIN,
                $dto
            ));
        }
    }

    private function getInvoices(IntegrationRoutingItemDTO $DTO): array
    {
        $invoiceIds = [];

        /** @var IntegrationRoutingItemDTO $routing */
        foreach ($DTO->route as $item) {
            if ($item['node']['type'] !== 'location') {
                continue;
            }

            $invoiceIds[] = $item['node']['value']['client_id'];
        }

        return $invoiceIds;
    }

    private function getSectorId(array $invoiceIds, int $defaultSectorId): int
    {
        /** @var InvoiceQuery $query */
        $query = app(InvoiceQuery::class);

        foreach ($invoiceIds as $invoiceId) {
            $invoice = $query->getById($invoiceId);

            if ($sector = $invoice?->receiver?->sector) {
                return $sector->id;
            }
        }

        return $defaultSectorId;
    }

    private function getCourierId(string $carNumber): int
    {
        /** @var CourierQuery $query */
        $query = app(CourierQuery::class);

        return $query->getByCarNumber($carNumber)->id;
    }
}
