<?php

declare(strict_types=1);

namespace App\Module\Gateway\Contracts\Integration;

use Illuminate\Http\Client\Response;

interface HttpClientRequest
{
    public function makeRequest(string $method, string $path, array $requestBody = null): Response;
}
