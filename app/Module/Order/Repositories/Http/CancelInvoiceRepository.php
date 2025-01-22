<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Http;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Order\Contracts\Repositories\Integration\CancelInvoiceRepository as CancelInvoiceRepositoryRepositoryContract;
use App\Module\Order\DTO\CancelInvoiceDTO;

final class CancelInvoiceRepository implements CancelInvoiceRepositoryRepositoryContract
{
    public function __construct(
        private readonly HttpClientRequest $client,
    ) {
    }

    public function cancel(CancelInvoiceDTO $DTO): void
    {
        $data = [
            'comment'  => $DTO->comment,
            'sourceId' => $DTO->sourceId,
            'userId'   => $DTO->userId,
        ];

        $this->client->makeRequest(
            'POST',
            sprintf("/cabinet/api/v2/logistics-info/%d/cancel", $DTO->id),
            $data,
        );
    }
}
