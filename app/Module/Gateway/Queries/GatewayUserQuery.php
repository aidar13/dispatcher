<?php

declare(strict_types=1);

namespace App\Module\Gateway\Queries;

use App\Module\Gateway\Contracts\AuthRepository;
use App\Module\Gateway\Contracts\GatewayUserQuery as GatewayUserQueryContract;
use App\Module\Gateway\DTO\GatewayUserCollectionDTO;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\GatewayUser;
use App\ValueObjects\PhoneNumber;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class GatewayUserQuery implements GatewayUserQueryContract
{
    private AuthRepository $repository;
    private string $url;

    public function __construct(AuthRepository $repository)
    {
        $this->url        = config('gateway.url');
        $this->repository = $repository;
    }

    public function getUsersWithFilter(GatewayUserDTO $dto): ?Collection
    {
        $accessToken = $this->repository->auth();

        $path     = '/gateway/users';
        $response = Http::withToken($accessToken)
            ->get(sprintf('%s%s', $this->url, $path), [
                'ids'        => $dto->ids,
                'email'      => $dto->email,
                'roleId'     => $dto->roleId,
                'roleIds'    => $dto->roleIds,
                'isExtended' => $dto->isExtended,
                'statusId'   => $dto->statusId,
                'limit'      => $dto->limit
            ]);

        if ($dto->needLog) {
            Log::info("Берем пользоваталей с фильтром из гейтвея", [
                'dto'      => $dto,
                'response' => $response->body(),
            ]);
        }

        if ($response->failed()) {
            throw new DomainException('Не удалось получит пользователей по фильтру:' . $response->json('message') ?? '');
        }

        return GatewayUserCollectionDTO::fromCollection(collect($response->json('data')));
    }

    public function find(int $id): GatewayUser
    {
        $accessToken = $this->repository->auth();
        $path        = "/gateway/users/{$id}";

        $response = Http::withToken($accessToken)->get($this->url . $path);

        if ($response->failed()) {
            throw new DomainException("В Gateway нету пользователя с идентификатором $id");
        }

        return GatewayUser::fromArray((array)$response->json());
    }

    public function hasPhoneNumber(PhoneNumber $number): bool
    {
        $path     = "/gateway/users/phone/{$number->getPhone()}/check";
        $response = Http::get($this->url . $path);

        return $response->status() !== 209;
    }
}
