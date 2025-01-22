<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Integrations;

use App\Module\DispatcherSector\Contracts\Integrations\Repositories\CreateDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\DestroyDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\UpdateDispatcherSectorIntegrationRepository;
use App\Module\DispatcherSector\DTO\IntegrationDispatcherSectorDTO;
use App\Module\Gateway\Contracts\AuthRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class DispatcherSectorIntegrationRepository implements CreateDispatcherSectorIntegrationRepository, UpdateDispatcherSectorIntegrationRepository, DestroyDispatcherSectorIntegrationRepository
{
    private string $url;
    private string $accessToken;

    public function __construct(private readonly AuthRepository $authRepository)
    {
        $this->url         = config('gateway.url');
        $this->accessToken = $this->authRepository->auth();
    }

    public function logging($path, $response, $data = []): void
    {
        Log::info("Запрос в микросервис кабинет (Сектора диспетчера)", [
            'path'         => $this->url . $path,
            'requestBody'  => $data,
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \DomainException("Ошибка при Http запросе, ошибка: {$response->body()}, по url: {$this->url}{$path}");
        }
    }

    public function create(IntegrationDispatcherSectorDTO $DTO): void
    {
        $path = "/cabinet/api/dispatchers_sector";

        $data = [
            'id'                => $DTO->id,
            'name'              => $DTO->name,
            'cityId'            => $DTO->cityId,
            'deliveryManagerId' => $DTO->deliveryManagerId,
            'description'       => $DTO->description,
            'coordinates'       => $DTO->coordinates,
            'userIds'           => $DTO->dispatcherIds,
        ];

        $response = Http::withToken($this->accessToken)
            ->post($this->url . $path, $data);

        $this->logging($path, $response, $data);
    }

    public function update(IntegrationDispatcherSectorDTO $DTO): void
    {
        $path = "/cabinet/api/dispatchers_sector/" . $DTO->id;

        $data = [
            'name'              => $DTO->name,
            'cityId'            => $DTO->cityId,
            'deliveryManagerId' => $DTO->deliveryManagerId,
            'userIds'           => $DTO->dispatcherIds,
            'description'       => $DTO->description,
            'coordinates'       => $DTO->coordinates
        ];

        $response = Http::withToken($this->accessToken)
            ->put($this->url . $path, $data);

        $this->logging($path, $response, $data);
    }

    public function destroy(int $dispatcherSectorId): void
    {
        $path = "/cabinet/api/dispatchers_sector/" . $dispatcherSectorId;

        $response = Http::withToken($this->accessToken)
            ->delete($this->url . $path);

        $this->logging($path, $response);
    }
}
