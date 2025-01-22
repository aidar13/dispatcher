<?php

declare(strict_types=1);

namespace App\Module\Planning\Handlers;

use App\Module\Planning\Commands\DeleteContainerInvoiceCommand;
use App\Module\Planning\Commands\DeleteContainerInvoicesCommand;

final class DeleteContainerInvoicesHandler
{
    public function handle(DeleteContainerInvoicesCommand $command): void
    {
        foreach ($command->DTO->invoiceIds as $invoiceId) {
            dispatch(new DeleteContainerInvoiceCommand(
                $command->DTO->containerId,
                (int)$invoiceId
            ));
        }
    }
}
