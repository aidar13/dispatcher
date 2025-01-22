<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\AttachInvoicesToContainerCommand;
use App\Module\Planning\Commands\CreateContainerInvoicesCommand;
use App\Module\Planning\Commands\DetachInvoicesFromContainerCommand;
use App\Module\Planning\Contracts\Queries\ContainerQuery;

final class AttachInvoicesToContainerHandler
{
    public function __construct(private readonly ContainerQuery $containerQuery)
    {
    }

    public function handle(AttachInvoicesToContainerCommand $command): void
    {
        $container = $this->containerQuery->getById($command->DTO->containerId);

        dispatch(new DetachInvoicesFromContainerCommand($command->DTO->invoiceIds));

        dispatch(new CreateContainerInvoicesCommand(
            $container->id,
            collect($command->DTO->invoiceIds)
        ));
    }
}
