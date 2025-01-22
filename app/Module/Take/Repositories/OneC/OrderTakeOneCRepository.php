<?php

declare(strict_types=1);

namespace App\Module\Take\Repositories\OneC;

use App\Exceptions\RequestOneCException;
use App\Libraries\Codes\OneCCodes;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\Take\Contracts\Repositories\UpdateOrderTakeRepository;
use App\Module\Take\DTO\IntegrationOneC\UpdateOrderTakeOneCDTO;
use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Facades\Log;

final class OrderTakeOneCRepository implements UpdateOrderTakeRepository
{
    public function __construct(
        private readonly HttpClientOneC $oneCClient,
        private readonly Integration1CConfigContract $integration1CConfigContract,
    ) {
    }

    /**
     * @throws RequestOneCException
     */
    public function update(OrderTake $take): void
    {
        $DTO      = UpdateOrderTakeOneCDTO::fromModel($take);
        $config   = $this->integration1CConfigContract->getMobileAppConfig();
        $response = $this->oneCClient->makeRequest(
            $config,
            'POST',
            OneCCodes::ZABOR,
            $DTO->toArray(),
        );

        Log::info('Смена статуса забора в 1С ', [
            'uri'          => $config->uri,
            'url'          => OneCCodes::ZABOR,
            'requestBody'  => $DTO->toArray(),
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new RequestOneCException('Ошибка при смене статуса забора ' . $response->body());
        }
    }
}
