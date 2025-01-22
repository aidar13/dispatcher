<?php

declare(strict_types=1);

namespace App\Module\Take\Repositories\Eloquent\Integration;

use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\Order\Models\Invoice;
use App\Module\Take\Contracts\Repositories\Integration\AssignCourierToOrderIn1CRepository as AssignCourierToOrderIn1CRepositoryContract;

final class AssignCourierToOrderIn1CRepository implements AssignCourierToOrderIn1CRepositoryContract
{
    public function __construct(
        private readonly HttpClientOneC $httpClient,
        private readonly Integration1CConfigContract $configService
    ) {
    }

    public function assignCourierToOrder(Invoice $invoice, int $courierId, ?string $orderNumber): void
    {
        $config = $this->configService->getMobileAppConfig();

        $response = $this->httpClient->makeRequest($config, 'POST', '/Integrationmac/courierpickup', [
            'НомерЗаказа' => $invoice->order->getNumber($orderNumber),
            'ГодЗаказа'   => $invoice->order->getYear(),
            'КодКурьера'  => $courierId
        ]);

        if (!empty($response['Error'])) {
            throw new \DomainException(
                'Не удалось назначить курьера на забор '
                . print_r([
                    'invoice_number' => $invoice->invoice_number,
                    'courierId'      => $courierId,
                    'response'       => $response->body()
                ], true)
            );
        }
    }
}
