<?php

declare(strict_types=1);

namespace App\Module\Order\Repositories\Http;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Order\Contracts\Repositories\Integration\CreateFastDeliveryOrderRepository;
use App\Module\Order\DTO\Integration\CreateFastDeliveryOrderDTO;
use App\Module\Order\DTO\Integration\FastDeliveryOrderDTO;

final class FastDeliveryOrderRepository implements CreateFastDeliveryOrderRepository
{
    public function __construct(
        private readonly HttpClientRequest $client
    ) {
    }

    public function create(CreateFastDeliveryOrderDTO $DTO): FastDeliveryOrderDTO
    {
        $response = $this->client->makeRequest(
            'POST',
            '/delivery/api/orders',
            $DTO->getRequestPayload()
        );

        return FastDeliveryOrderDTO::fromArray($response->json('data', []));
    }
}
