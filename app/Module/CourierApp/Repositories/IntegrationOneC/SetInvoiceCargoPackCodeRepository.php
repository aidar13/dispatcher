<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Repositories\IntegrationOneC;

use App\Exceptions\RequestOneCException;
use App\Libraries\Codes\OneCCodes;
use App\Module\CourierApp\DTO\IntegrationOneC\SetPackCodeOneCDTO;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use Illuminate\Support\Facades\Log;
use App\Module\CourierApp\Contracts\Repositories\OrderTake\SetInvoiceCargoPackCodeRepository as SetInvoiceCargoPackCodeRepositoryContract;

final class SetInvoiceCargoPackCodeRepository implements SetInvoiceCargoPackCodeRepositoryContract
{
    public function __construct(
        private readonly HttpClientOneC $client1cHttp,
        private readonly Integration1CConfigContract $configService
    ) {
    }

    /**
     * @throws RequestOneCException
     */
    public function setPackCode(SetPackCodeOneCDTO $DTO): string
    {
        $config   = $this->configService->getMainConfig();
        $response = $this->client1cHttp->makeRequest(
            $config,
            'POST',
            OneCCodes::SET_PACK_CODE,
            $DTO->toArray(),
        );

        Log::info('Присваиваем штрих код накладной ', [
            'url'      => OneCCodes::SET_PACK_CODE,
            'data'     => $DTO->toArray(),
            'response' => $response->json(),
            'status'   => $response->status(),
            'config'   => $config,
        ]);

        if ($response->failed()) {
            throw new RequestOneCException($response);
        }

        return $response->json()['pack_size'];
    }
}
