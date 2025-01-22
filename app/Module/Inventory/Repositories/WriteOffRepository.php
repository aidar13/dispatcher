<?php

declare(strict_types=1);

namespace App\Module\Inventory\Repositories;

use App\Exceptions\InventoryException;
use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Inventory\Contracts\Repositories\CreateWriteOffRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Module\Inventory\DTO\Integration\IntegrationWriteOffDTO;

final class WriteOffRepository implements CreateWriteOffRepository
{
    private string $url;

    public function __construct(
        private readonly AuthRepository $repository,
    ) {
        $this->url = config('gateway.url');
    }

    /**
     * @throws InventoryException
     */
    public function create(IntegrationWriteOffDTO $DTO): void
    {
        $accessToken = $this->repository->auth();
        $path        = '/inventory/api/write-offs';

        $response = Http::withToken($accessToken)
            ->post($this->url . $path, $DTO->toArray());

        Log::info('Создание списания в сервисе inventory', [
            'uri'      => $this->url . $path,
            'DTO'      => $DTO->toArray(),
            'status'   => $response->status(),
            'response' => $response->object(),
        ]);

        if ($response->failed()) {
            throw new InventoryException('Ошибка в создание списания в ТМЦ: ' . $response->body());
        }
    }
}
