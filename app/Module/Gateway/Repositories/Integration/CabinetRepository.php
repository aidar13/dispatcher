<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repositories\Integration;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Gateway\Contracts\Integration\SendToCabinetRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

final class CabinetRepository implements SendToCabinetRepository, ShouldQueue
{
    public function __construct(private readonly HttpClientRequest $client)
    {
    }

    public function assignOrderTakes(array $orderIds, int $courierId, bool $storeOrderStatus): void
    {
        $path = "/cabinet/api/dispatcher-admin/take/orders/assign";

        $data = [
            'courier_id'         => $courierId,
            'order_ids'          => $orderIds,
            'store_order_status' => $storeOrderStatus
        ];

        $response = $this->client->makeRequest(
            'POST',
            $path,
            $data,
        );

        Log::info("Присваиваем курьера к заказам в кабинет из дисп, orderIds: " . json_encode($orderIds) . " courierId: $courierId", [
            'response' => $response->status(),
            'data'     => $data,
            'path'     => $path
        ]);
    }
}
