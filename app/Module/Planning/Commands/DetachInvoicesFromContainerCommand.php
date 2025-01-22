<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

final class DetachInvoicesFromContainerCommand
{
    public function __construct(public readonly array $invoiceIds)
    {
    }
}
