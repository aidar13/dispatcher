<?php

declare(strict_types=1);

namespace App\Module\Courier\Handlers;

use App\Module\Courier\Commands\UpdateCourierPhoneNumberInGatewayCommand;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Gateway\Contracts\Integration\HttpClientRequest;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Log;

final readonly class UpdateCourierPhoneNumberInGatewayHandler
{
    public function __construct(
        private CourierQuery $courierQuery,
        private HttpClientRequest $clientRequest
    ) {
    }

    public function handle(UpdateCourierPhoneNumberInGatewayCommand $command): void
    {
        $courier = $this->courierQuery->getById($command->id);

        $path = "/gateway/users/$courier->user_id/change-phone";
        $data = [
            'phone' => $courier->phone_number,
        ];

        $response = $this->clientRequest->makeRequest(
            'POST',
            $path,
            $data,
        );

        Log::info("Редактирование номера телефона курьера в gateway", [
            'courierId' => $courier->id,
            'data'      => $data,
            'response'  => $response->json(),
            'status'    => $response->status(),
        ]);

        if ($response->failed()) {
            throw new HttpClientException("Ошибка при редактировании номера телефона курьера в gateway: " . $response->body());
        }
    }
}
