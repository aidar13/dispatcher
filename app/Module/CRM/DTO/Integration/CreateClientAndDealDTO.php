<?php

declare(strict_types=1);

namespace App\Module\CRM\DTO\Integration;

use Illuminate\Support\Arr;

final class CreateClientAndDealDTO
{
    public int $clientSourceId;
    public ?int $clientManagerId = null;
    public array $phones;
    public array $clientFields = [];
    public bool $createDealIfExistsClient = true;
    public array $deals = [];

    public function setClientSourceId(int $clientSourceId): void
    {
        $this->clientSourceId = $clientSourceId;
    }

    public function setPhones(array $phones): void
    {
        $this->phones = $phones;
    }

    public function pushClientField(int $id, ?string $value): void
    {
        $this->clientFields[] = [
            'id'    => $id,
            'value' => $value
        ];
    }

    public function setCreateDealIfExistsClient(bool $createDealIfExistsClient): void
    {
        $this->createDealIfExistsClient = $createDealIfExistsClient;
    }

    public function pushDeal(array $data): void
    {
        $dealFields = [];

        foreach (Arr::get($data, 'dealFields') as $field) {
            $dealFields[] = [
                'id'    => Arr::get($field, 'id'),
                'value' => Arr::get($field, 'value')
            ];
        }

        $this->deals[] = [
            'dealFunnelStepId' => Arr::get($data, 'dealFunnelStepId'),
            'dealStatusId'     => Arr::get($data, 'dealStatusId'),
            'dealFields'       => $dealFields
        ];
    }
}
