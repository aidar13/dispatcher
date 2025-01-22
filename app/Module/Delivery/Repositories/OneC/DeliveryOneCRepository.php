<?php

declare(strict_types=1);

namespace App\Module\Delivery\Repositories\OneC;

use App\Exceptions\RequestOneCException;
use App\Libraries\Codes\OneCCodes;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\DTO\IntegrationOneC\UpdateDeliveryOneCDTO;
use App\Module\Delivery\Models\Delivery;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use Illuminate\Support\Facades\Log;

final class DeliveryOneCRepository implements UpdateDeliveryRepository
{
    public function __construct(
        private readonly HttpClientOneC $oneCClient,
        private readonly Integration1CConfigContract $integration1CConfigContract,
    ) {
    }

    /**
     * @throws RequestOneCException
     */
    public function update(Delivery $delivery): void
    {
        $DTO      = UpdateDeliveryOneCDTO::fromModel($delivery);
        $config   = $this->integration1CConfigContract->getMobileAppConfig();
        $response = $this->oneCClient->makeRequest(
            $config,
            'POST',
            OneCCodes::DOSTAVKA,
            $DTO->toArray(),
        );

        Log::info('Смена статуса доставки в 1С ', [
            'uri'          => $config->uri,
            'url'          => OneCCodes::DOSTAVKA,
            'requestBody'  => $DTO->toArray(),
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new RequestOneCException('Ошибка при смене статуса доставки ' . $response->body());
        }
    }
}
