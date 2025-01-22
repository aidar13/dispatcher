<?php

declare(strict_types=1);

namespace App\Module\Gateway\Repositories\Integration;

use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class Request implements HttpClientRequest
{
    private string $url;
    private string $accessToken;

    public function __construct(private readonly AuthRepository $authRepository)
    {
        $this->url         = config('gateway.url');
        $this->accessToken = $this->authRepository->auth();
    }

    public function makeRequest(string $method, string $path, array $requestBody = null): Response
    {
        $this->url .= $path;

        $response = Http::withToken($this->accessToken)
            ->{strtolower($method)}($this->url, $requestBody);

        Log::info("Http запрос в диспетчерском на внешние сервисы", [
            'path'         => $this->url,
            'requestBody'  => $requestBody,
            'response'     => $response->object(),
            'responseCode' => $response->status(),
        ]);

        if ($response->failed()) {
            throw new \DomainException("Ошибка при Http запросе по url: $this->url, ошибка: {$response->body()}");
        }

        return $response;
    }
}
