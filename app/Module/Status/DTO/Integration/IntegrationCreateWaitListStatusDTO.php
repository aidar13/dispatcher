<?php

declare(strict_types=1);

namespace App\Module\Status\DTO\Integration;

use App\Traits\ToArrayTrait;

final class IntegrationCreateWaitListStatusDTO
{
    use ToArrayTrait;

    public ?int $invoiceId;
    public ?int $orderId;
    public ?int $code;
    public ?string $value;
    public ?int $parentId;
    public ?string $comment;
    public ?int $stateId;
    public ?string $source;

    public function setInvoiceId(?int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    public function setOrderId(?int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function setCode(?int $code): void
    {
        $this->code = $code;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setStateId(?int $stateId): void
    {
        $this->stateId = $stateId;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }
}
