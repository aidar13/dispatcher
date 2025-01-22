<?php

declare(strict_types=1);

namespace App\Module\Routing\Repositories;

use App\Module\DispatcherSector\Models\Sector;
use App\Module\Notification\Commands\SendTelegramMessageCommand;
use App\Module\Notification\Models\TelegramChat;
use App\Module\Routing\Contracts\Repositories\IntegrationZoneRepository as IntegrationZoneRepositoryContract;
use DomainException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class IntegrationZoneRepository implements IntegrationZoneRepositoryContract
{
    private string $url;
    private string $token;
    private int $companyId;

    public function __construct()
    {
        $this->url       = config('yandex-routing.url');
        $this->token     = config('yandex-routing.token');
        $this->companyId = config('yandex-routing.companyId');
    }

    public function create(Sector $sector): void
    {
        $path = "/api/v1/reference-book/companies/$this->companyId/zones";
        $data = [$this->prepareZoneData($sector)];

        $response = $this->sendRequest('post', $path, $data, "Ошибка при создание зоны в yandex маршрутизации сектора ID: {$sector->id}");

        Log::info("Создан зона в yandex маршрутизации: " . $sector->id, [
            'status'   => $response->status(),
            'response' => $response->json(),
            'data'     => $data
        ]);
    }

    public function update(Sector $sector): void
    {
        $path = "/api/v1/reference-book/companies/$this->companyId/zones/$sector->id";
        $data = $this->prepareZoneData($sector);

        $response = $this->sendRequest('patch', $path, $data, "Ошибка при редактировании зоны в yandex маршрутизации сектора ID: {$sector->id}");

        Log::info("Отредактирована зона в yandex маршрутизации: " . $sector->id, [
            'status'   => $response->status(),
            'response' => $response->json(),
            'data'     => $data
        ]);
    }

    public function delete(Sector $sector): void
    {
        $path = "/api/v1/reference-book/companies/$this->companyId/zones/$sector->id";
        $data = $this->prepareZoneData($sector);

        $response = $this->sendRequest('delete', $path, $data, "Ошибка при удалении зоны в yandex маршрутизации сектора ID: {$sector->id}");

        Log::info("Удалена зона в yandex маршрутизации: " . $sector->id, [
            'status'   => $response->status(),
            'response' => $response->json(),
            'data'     => $data
        ]);
    }

    private function getCoordinates(Sector $sector): array
    {
        $coordinates = [];

        foreach (json_decode($sector->coordinates, true) as $coordinate) {
            $coordinates[] = [$coordinate[1], $coordinate[0]];
        }

        return [$coordinates];
    }

    private function prepareZoneData(Sector $sector): array
    {
        return [
            'id'         => (string)$sector->id,
            'number'     => $sector->getNameToYandex(),
            'color_edge' => $sector->color,
            'color_fill' => $sector->color,
            'polygon'    => [
                'type'        => 'Polygon',
                'coordinates' => $this->getCoordinates($sector)
            ]
        ];
    }

    private function sendRequest(string $method, string $path, array $data, string $errorMessage): Response
    {
        $response = Http::withHeaders([
            "Content-Type"  => "application/json",
            "authorization" => "Bearer " . $this->token,
        ])->$method($this->url . $path, $data);

        if ($response->failed()) {
            Log::info($errorMessage, [
                'body'   => $response->body(),
                'status' => $response->status()
            ]);

            dispatch(new SendTelegramMessageCommand(
                TelegramChat::ALERT_CHAT_ID,
                $errorMessage . json_encode($response->json())
            ));

            throw new DomainException($errorMessage);
        }

        return $response;
    }
}
