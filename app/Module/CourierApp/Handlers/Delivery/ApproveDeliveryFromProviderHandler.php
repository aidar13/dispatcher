<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery;

use App\Exceptions\RequestOneCException;
use App\Libraries\Codes\OneCCodes;
use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryFromProviderCommand;
use App\Module\Delivery\DTO\IntegrationOneC\UpdateDeliveryOneCDTO;
use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\Contracts\Integration\Integration1CConfigContract;
use App\Module\Order\Contracts\Queries\InvoiceQuery;
use Illuminate\Support\Facades\Log;

final readonly class ApproveDeliveryFromProviderHandler
{
    public function __construct(
        private InvoiceQuery $query,
        private HttpClientOneC $oneCClient,
        private Integration1CConfigContract $integration1CConfigContract,
    ) {
    }

    public function handle(ApproveDeliveryFromProviderCommand $command): void
    {
        $invoice = $this->query->getById($command->invoiceId, ['*'], ['statuses', 'receiver.dispatcherSector', 'container.fastDeliveryOrder']);

        Log::info('Доставка закрыта с правайдера : ' . $invoice->id);

        $DTO      = UpdateDeliveryOneCDTO::fromInvoiceModel($invoice);
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
