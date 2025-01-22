<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Http;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Order\Contracts\Repositories\Integration\ChangeInvoiceTakeDataRepository as ChangeInvoiceTakeDataRepositoryContract;
use App\Module\Take\DTO\ChangeTakeDateDTO;

final readonly class ChangeInvoiceTakeDataRepository implements ChangeInvoiceTakeDataRepositoryContract
{
    public function __construct(
        private HttpClientRequest $client,
    ) {
    }

    public function changeTakeDateByOrderInCabinet(ChangeTakeDateDTO $DTO): void
    {
        $data = [
            'order_id' => $DTO->orderId,
            'new_date' => $DTO->newDate,
            'userId'   => $DTO->userId,
            'periodId' => $DTO->periodId
        ];

        $this->client->makeRequest(
            'POST',
            '/cabinet/api/dispatcher-admin/take/order/change-date',
            $data
        );
    }
}
