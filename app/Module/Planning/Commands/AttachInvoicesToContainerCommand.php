<?php

declare(strict_types=1);

namespace App\Module\Planning\Commands;

use App\Module\Planning\DTO\AttachInvoicesToContainerDTO;

final class AttachInvoicesToContainerCommand
{
    public function __construct(public readonly AttachInvoicesToContainerDTO $DTO)
    {
    }
}
