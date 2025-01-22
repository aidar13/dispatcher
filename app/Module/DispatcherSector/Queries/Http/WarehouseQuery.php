<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Queries\Http;

use App\Module\DispatcherSector\Contracts\Queries\HttpWarehouseQuery;
use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;

final class WarehouseQuery implements HttpWarehouseQuery
{
    public function __construct(private readonly HttpClientRequest $request)
    {
    }

    public function getByCityId(int $cityId): ?WarehouseDTO
    {
        $path = '/cabinet/api/warehouses/info/' . $cityId;

        $response = $this->request->makeRequest('GET', $path);

        if ($response->failed()) {
            throw new \DomainException($response->body());
        }

        $data = $response->json('data');

        return !empty($data) ? WarehouseDTO::fromArray($data) : null;
    }
}
