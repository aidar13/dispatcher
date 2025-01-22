<?php

namespace App\Module\Take\Repositories\Eloquent\Integration;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Status\Models\StatusSource;
use App\Module\Take\Contracts\Repositories\Integration\SetWaitListStatusRepositoryIntegration as SetTakeWaitListStatusRepositoryIntegrationContract;

class SetTakeWaitListStatusRepositoryIntegration implements SetTakeWaitListStatusRepositoryIntegrationContract
{
    public function __construct(
        private readonly HttpClientRequest $client
    ) {
    }

    public function setTakeWaitListStatusInCabinet(int $orderId, int $code, ?int $userId): void
    {
        $this->client->makeRequest(
            'PUT',
            "/cabinet/api/courier-app/take_info/$orderId/set-wait-list-status",
            [
                'statusCode' => $code,
                'sourceId'   => StatusSource::ID_CABINET,
                'userId'     => $userId
            ]
        );
    }
}
