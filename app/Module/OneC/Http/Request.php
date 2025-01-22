<?php

declare(strict_types=1);

namespace App\Module\OneC\Http;

use App\Module\OneC\Contracts\Integration\HttpClientOneC;
use App\Module\OneC\DTO\Integration\Integration1CConfigDTO;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Request implements HttpClientOneC
{
    /**
     * Запрос в 1С
     * @param Integration1CConfigDTO $config
     * @param string $method
     * @param string $url
     * @param array $requestBody
     * @return Response
     */
    public function makeRequest(Integration1CConfigDTO $config, string $method, string $url, array $requestBody): Response
    {
        /** @var Response $response */
        $response = Http::baseUrl($config->uri)
            ->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
                'token'        => $config->token
            ])
            ->withBasicAuth($config->login, $config->password)
            ->{strtolower($method)}($url, $requestBody);

        Log::info("Запросы 1С по url [$method] - [$url]", [
            'uri'          => $config->uri,
            'responseCode' => $response->status(),
            'response'     => $response->object(),
            'requestBody'  => $requestBody,
        ]);

        return $response;
    }
}
