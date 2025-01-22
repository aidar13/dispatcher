<?php

declare(strict_types=1);

namespace App\Module\Routing\Repositories;

use App\Module\Courier\Models\Courier;
use App\Module\Courier\Models\CourierSector;
use App\Module\DispatcherSector\DTO\WarehouseDTO;
use App\Module\Routing\Contracts\Repositories\IntegrationRoutingRepository as IntegrationRoutingRepositoryContract;
use App\Module\Routing\DTO\IntegrationRoutingDTO;
use App\Module\Routing\Models\Routing;
use App\Module\Routing\Models\RoutingItem;
use DomainException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class IntegrationRoutingRepository implements IntegrationRoutingRepositoryContract
{
    private string $url;
    private string $token;

    public function __construct()
    {
        $this->url   = config('yandex-routing.url');
        $this->token = config('yandex-routing.token');
    }

    public function create(Routing $routing, WarehouseDTO $DTO): void
    {
        $path = '/vrs/api/v1/add/mvrp';

        $data = [
            'depot'     => [
                'id'          => '0',
                'ref'         => 'Склад',
                'time_window' => '07:00:00-22:00:00',
                'point'       => [
                    'lat' => (float)$DTO->latitude,
                    'lon' => (float)$DTO->longitude
                ]
            ],
            'vehicles'  => $this->getVehicles($routing),
            'options'   => [
                'time_zone' => 5,
                'quality'   => 'normal',
                'date'      => now()->format('Y-m-d')
            ],
            'locations' => $this->getLocations($routing)
        ];

        $response = Http::withHeaders([
            "Content-Type"  => "application/json",
            "authorization" => "Bearer " . $this->token,
        ])->post($this->url . $path, $data);

        if ($response->failed()) {
            Log::info("Ошибка при создание задачи в yandex маршрутизации: " . $response->body());
            throw new DomainException('Ошибка при создание задачи в yandex маршрутизации: ' . $response->body());
        }

        Log::info("Создан задача в yandex маршрутизации: " . $routing->id, [
            'status'   => $response->status(),
            'response' => $response->json(),
            'data'     => $data
        ]);

        $routing->task_id = $response->json('id');
        $routing->update();
    }

    public function getByTaskId(string $taskId): IntegrationRoutingDTO
    {
        $path = '/vrs/api/v1/result/mvrp/' . $taskId;

        /** @var Response $response */
        $response = Http::withHeaders([
            "Content-Type"  => "application/json",
            "authorization" => "Bearer " . $this->token,
        ])->get($this->url . $path);

        if ($response->failed()) {
            Log::info("Ошибка при получении задачи из yandex маршрутизации: " . $response->body());
            throw new DomainException('Ошибка при получении задачи из yandex маршрутизации: ' . $response->body());
        }

        Log::info("Получен задача из yandex маршрутизации: " . $taskId, [
            'status'   => $response->status(),
            'response' => $response->json()
        ]);

        return IntegrationRoutingDTO::fromResponse($response);
    }

    /**
     * @psalm-suppress UndefinedMagicPropertyFetch
     */
    private function getLocations(Routing $routing): array
    {
        $locations = [];

        /** @var RoutingItem $item */
        foreach ($routing->items as $item) {
            $locations[] = [
                "id"            => $item->isTypeTake() ? $item->client->number : $item->client->invoice_number,
                'time_window'   => '08:00:00-18:00:00',
                'client_id'     => $item->client->id,
                'point'         => [
                    'lat' => $item->isTypeTake() ? (float)$item->client->sender->latitude : (float)$item->client->receiver->latitude,
                    'lon' => $item->isTypeTake() ? (float)$item->client->sender->longitude : (float)$item->client->receiver->longitude
                ],
                "shipment_size" => [
                    "weight_kg" => $item->isTypeTake() ? (int)$item->client->orderTakes->sum('weight') : (int)$item->client->cargo->weight
                ],
                "type"          => $item->isTypeTake() ? Routing::TYPE_TAKE : Routing::TYPE_DELIVERY,
                'can_be_split'  => false
            ];
        }

        return $locations;
    }

    private function getVehicles(Routing $routing): array
    {
        if ($routing->isTypeSingleCar()) {
            return [
                [
                    'id'       => $routing->courier->car->number,
                    'capacity' => [
                        "volume_cbm" => $routing->courier->car->cubature
                    ],
                    'shifts'   => [
                        [
                            'id'          => '0',
                            'time_window' => '08:00:00-19:00:00'
                        ]
                    ]
                ]
            ];
        }

        $vehicles = [];

        /** @var Courier $courier */
        foreach ($routing->dispatcherSector->getRoutingEnabledCouriers() as $courier) {
            $allowedSectors = collect($courier->getAllowedCourierSectors())
                ->map(function (CourierSector $courierSector) {
                    return $courierSector->sector->getNameToYandex();
                })->toArray();

            $vehicles[] = [
                'id'       => $courier->car->number,
                'capacity' => [
                    "volume_cbm" => $courier->car->cubature
                ],
                'shifts'   => [
                    [
                        'id'          => '0',
                        'time_window' => '08:00:00-19:00:00'
                    ]
                ],
                'allowed_zones' => $allowedSectors
            ];
        }

        return $vehicles;
    }
}
