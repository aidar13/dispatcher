<?php

declare(strict_types=1);

namespace App\Module\Take\Repositories\Eloquent\Integration;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Status\DTO\SendOrderStatusDTO;
use App\Module\Take\Contracts\Repositories\Integration\IntegrationOrderStatusRepository as IntegrationOrderStatusRepositoryContract;

final readonly class IntegrationOrderStatusRepository implements IntegrationOrderStatusRepositoryContract
{
    public function __construct(
        private HttpClientRequest $client
    ) {
    }

    public function sendStatusToCabinet(SendOrderStatusDTO $DTO): void
    {
        $data = [
            'invoice_number' => $DTO->invoiceNumber,
            'code'           => $DTO->code,
            'created_at'     => $DTO->createdAt,
            'statusSourceId' => $DTO->statusSourceId,
            'userId'         => $DTO->userId
        ];

        $this->client->makeRequest(
            'POST',
            '/cabinet/api/order/status-internal',
            $data
        );
    }
}
