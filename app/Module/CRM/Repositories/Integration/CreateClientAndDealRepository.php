<?php

declare(strict_types=1);

namespace App\Module\CRM\Repositories\Integration;

use App\Module\CRM\Contracts\Repositories\CreateClientAndDealRepository as CreateClientAndDealRepositoryContract;
use App\Module\CRM\DTO\Integration\CreateClientAndDealDTO;
use DomainException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class CreateClientAndDealRepository implements CreateClientAndDealRepositoryContract
{
    private string $uri;

    public function __construct()
    {
        $this->uri = config('urls.mindsale.url');
    }

    public function createClientsDeals(CreateClientAndDealDTO $DTO): void
    {
        $data = [
            'data' => [
                [
                    'clientSourceId'           => $DTO->clientSourceId,
                    'clientManagerId'          => $DTO->clientManagerId,
                    'phones'                   => $DTO->phones,
                    'clientFields'             => $DTO->clientFields,
                    'createDealIfExistsClient' => $DTO->createDealIfExistsClient,
                    'deals'                    => $DTO->deals
                ]
            ]
        ];

        if (!app()->environment('production')) {
            return;
        }

        $response = Http::post($this->uri . '/addclientsdeals', $data);

        Log::info("Отправка запроса на создание сделки:", [
            $response->body()
        ]);

        if ($response->failed()) {
            throw new DomainException('Создание сделки в MindSales не прошла.' . $response->body());
        }
    }
}
