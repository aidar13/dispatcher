<?php

declare(strict_types=1);

namespace App\Module\Routing\Handlers;

use App\Exceptions\DomainExceptionWithErrors;
use App\Module\Courier\Contracts\Queries\CourierQuery;
use App\Module\Courier\Models\Courier;
use App\Module\Routing\Commands\CreateCourierRoutingCommand;
use App\Module\Routing\Contracts\Repositories\CreateRoutingRepository;
use App\Module\Routing\DTO\CreateCourierRoutingDTO;
use App\Module\Routing\Events\RoutingCreatedEvent;
use App\Module\Routing\Models\Routing;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;
use Illuminate\Support\Facades\Log;

final readonly class CreateCourierRoutingHandler
{
    public function __construct(
        private CreateRoutingRepository $repository,
        private SettingsService $settingsService,
    ) {
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    public function handle(CreateCourierRoutingCommand $command): void
    {
        if (!$this->settingsService->isEnabled(Settings::YANDEX_ROUTING)) {
            return;
        }

        $courier = $this->getCourier($command->DTO);

        if (!$courier->routingEnabled()) {
            Log::info('У курьера выключен маршрутизация! #' . $courier->id);

            if ($command->DTO->userId) {
                throw new DomainExceptionWithErrors('У курьера выключен маршрутизация!');
            }

            return;
        }

        $routing             = new Routing();
        $routing->type       = Routing::TYPE_SINGLE_CAR;
        $routing->courier_id = $courier->id;
        $routing->user_id    = $command->DTO->userId ?? $courier->user_id;

        $this->repository->create($routing);

        event(new RoutingCreatedEvent($routing->id));
    }

    /**
     * @throws DomainExceptionWithErrors
     */
    private function getCourier(CreateCourierRoutingDTO $DTO): Courier
    {
        /** @var CourierQuery $query */
        $query = app(CourierQuery::class);

        if ($DTO->courierId) {
            return $query->getById($DTO->courierId);
        }

        $courier = $query->getByUserId($DTO->userId);

        if (!$courier) {
            throw new DomainExceptionWithErrors('Не удалось наити курьера');
        }

        return $courier;
    }
}
