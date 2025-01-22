<?php

declare(strict_types=1);

namespace App\Module\Order\Handlers;

use App\Module\Order\Commands\SetInvoicesWaveCommand;
use App\Module\Order\Commands\SetInvoiceWaveCommand;

final class SetInvoicesWaveHandler
{
    public function handle(SetInvoicesWaveCommand $command): void
    {
        foreach ($command->invoiceIds as $invoiceId) {
            dispatch(new SetInvoiceWaveCommand(
                (int)$invoiceId,
                $command->waveId
            ));
        }
    }
}
