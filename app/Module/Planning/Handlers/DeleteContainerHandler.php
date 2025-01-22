<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\DeleteContainerCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;
use App\Module\Planning\Contracts\Repositories\DeleteContainerRepository;
use App\Module\Planning\Exceptions\CannotDeleteContainerException;

final class DeleteContainerHandler
{
    public function __construct(
        private readonly ContainerQuery $query,
        private readonly DeleteContainerRepository $repository
    ) {
    }

    /**
     * @throws CannotDeleteContainerException
     */
    public function handle(DeleteContainerCommand $command): void
    {
        $container = $this->query->getById($command->containerId);

        if (!$container->invoices->isEmpty()) {
            throw new CannotDeleteContainerException('Контейнер имеет накладные');
        }

        $this->repository->delete($container);
    }
}
