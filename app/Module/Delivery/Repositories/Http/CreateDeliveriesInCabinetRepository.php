<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\Http;

use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use App\Module\Delivery\Contracts\Repositories\Integration\CreateDeliveriesInCabinetRepository as CreateDeliveriesInCabinetRepositoryContract;
use Illuminate\Support\Facades\Log;

final class CreateDeliveriesInCabinetRepository implements CreateDeliveriesInCabinetRepositoryContract
{
    public function __construct(
        private readonly HttpClientRequest $client,
    ) {
    }

    public function createDeliveries(string $routeSheetNumber, int $courierId): void
    {
        $path = "/cabinet/api/courier-app/route-sheet";

        $data = [
            'routeSheet' => $routeSheetNumber,
            'courierId'  => $courierId
        ];

        $response = $this->client->makeRequest(
            'POST',
            $path,
            $data
        );

        Log::info("Присваиваем курьера к марш листу: routeSheetNumber: $routeSheetNumber, courierId: $courierId", [
            'response' => $response->status(),
            'data'     => $data,
            'path'     => $path
        ]);
    }
}
