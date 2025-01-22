<?php

declare(strict_types=1);

namespace App\Module\Status\DTO;

use App\Helpers\DateHelper;
use App\Module\Status\Models\StatusSource;
use Illuminate\Support\Carbon;

final class SendOrderStatusDTO
{
    public string $invoiceNumber;
    public int $code;
    public string $createdAt;
    public int $userId;
    public int $statusSourceId = StatusSource::ID_DISPATCHER;

    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setCreatedAt(Carbon $createdAt): void
    {
        $this->createdAt = DateHelper::getISOFormat($createdAt);
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setStatusSourceId(int $statusSourceId): void
    {
        $this->statusSourceId = $statusSourceId;
    }
}
