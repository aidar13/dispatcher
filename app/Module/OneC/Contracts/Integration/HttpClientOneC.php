<?php

declare(strict_types=1);

namespace App\Module\OneC\Contracts\Integration;

use App\Module\OneC\DTO\Integration\Integration1CConfigDTO;
use Illuminate\Http\Client\Response;

interface HttpClientOneC
{
    public function makeRequest(Integration1CConfigDTO $config, string $method, string $url, array $requestBody): Response;
}
