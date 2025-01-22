<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\IntegrationOneC;

final class SetPackCodeOneCDTO
{
    public string $invoiceNumber;
    public string $packCode;

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function setPackCode(string $packCode): void
    {
        $this->packCode = $packCode;
    }

    public function toArray(): array
    {
        return [
            'invoiceNumber' => $this->invoiceNumber,
            'packcode'      => $this->packCode
        ];
    }
}
