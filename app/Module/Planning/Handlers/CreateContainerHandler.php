<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\CreateContainerCommand;
use App\Module\Planning\Commands\CreateContainerInvoicesCommand;
use App\Module\Planning\Contracts\Repositories\CreateContainerRepository;
use App\Module\Planning\Models\Container;

final readonly class CreateContainerHandler
{
    public function __construct(private CreateContainerRepository $containerRepository)
    {
    }

    public function handle(CreateContainerCommand $command): void
    {
        $container = new Container();
        $container->setSectorId($command->DTO->sectorId);
        $container->setWaveId($command->DTO->waveId);
        $container->setDate($command->DTO->date);
        $container->setCargoType($command->DTO->cargoType);
        $container->setUserId($command->userId);
        $container->setRoutingId($command->DTO->routingId);
        $container->setCourierId($command->DTO->courierId);
        $container->setTitle();

        $this->containerRepository->create($container);

        dispatch(new CreateContainerInvoicesCommand($container->id, $command->DTO->invoiceIds));
    }
}
