<?php

declare(strict_types=1);

namespace App\Module\DispatcherSector\Repositories\Integrations;

use App\Libraries\Codes\OneCCodes;
use App\Module\DispatcherSector\Contracts\Integrations\Repositories\SendSectorTo1CRepository as SendSectorTo1CRepositoryContract;
use App\Module\DispatcherSector\DTO\Integration\IntegrationSector1CDTO;
use App\Module\DispatcherSector\Models\Sector;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use Illuminate\Support\Facades\Log;

final class SendSectorTo1CRepository implements SendSectorTo1CRepositoryContract
{
    public function __construct(
        private readonly HttpClientOneC $httpClient,
        private readonly Integration1CConfigContract $configService
    ) {
    }

    public function sendSectorTo1C(IntegrationSector1CDTO $DTO): void
    {
        $config = $this->configService->getMainConfig();
        $data   = [
            'id'      => $DTO->id,
            'catalog' => Sector::ONE_C_NANE,
            'name'    => $DTO->name,
            'city_id' => $DTO->cityId
        ];

        $response = $this->httpClient->makeRequest($config, 'POST', OneCCodes::CATALOG, $data);

        Log::info("Создание сектора в 1С ", [
            'path'         => OneCCodes::CATALOG,
            'requestBody'  => $data,
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \DomainException(
                'Не удалось создать сектор в 1С '
                . print_r([
                    'id'      => $DTO->id,
                    'name'    => $DTO->name,
                    'city_id' => $DTO->cityId
                ], true)
            );
        }
    }
}
