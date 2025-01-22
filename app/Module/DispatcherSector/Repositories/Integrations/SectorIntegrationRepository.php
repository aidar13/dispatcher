<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Integrations;

use App\Module\DispatcherSector\Contracts\Integrations\Repositories\CreateSectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\DestroySectorIntegrationRepository;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\UpdateSectorIntegrationRepository;
use App\Module\DispatcherSector\DTO\IntegrationSectorDTO;
use App\Module\Gateway\Contracts\AuthRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class SectorIntegrationRepository implements CreateSectorIntegrationRepository, UpdateSectorIntegrationRepository, DestroySectorIntegrationRepository
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
        Log::info("Запрос в микросервис кабинет (Сектора доставки)", [
            'path'         => $this->url . $path,
            'requestBody'  => $data,
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \DomainException("Ошибка при Http запросе, ошибка: {$response->body()}, по url: {$this->url}{$path}");
        }
    }

    public function create(IntegrationSectorDTO $DTO): void
    {
        $path = "/cabinet/api/sector";

        $data = [
            'id'                    => $DTO->id,
            'name'                  => $DTO->name,
            'dispatchers_sector_id' => $DTO->dispatcherSectorId,
            'coordinates'           => $DTO->coordinates,
            'color'                 => $DTO->color
        ];

        $response = Http::withToken($this->accessToken)
            ->post($this->url . $path, $data);

        $this->logging($path, $response, $data);
    }

    public function update(IntegrationSectorDTO $DTO): void
    {
        $path = "/cabinet/api/sector/" . $DTO->id;

        $data = [
            'name'                  => $DTO->name,
            'dispatchers_sector_id' => $DTO->dispatcherSectorId,
            'coordinates'           => $DTO->coordinates,
            'color'                 => $DTO->color
        ];

        $response = Http::withToken($this->accessToken)
            ->put($this->url . $path, $data);

        $this->logging($path, $response, $data);
    }

    public function destroy(int $sectorId): void
    {
        $path = "/cabinet/api/sector/" . $sectorId;

        $response = Http::withToken($this->accessToken)
            ->delete($this->url . $path);

        $this->logging($path, $response);
    }
}
