<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Listeners\OrderTake;

use App\Http\Enums\RequestSource;
use App\Module\CourierApp\Events\OrderTake\CourierShortcomingFilesSavedEvent;
use App\Module\Status\Commands\Integration\IntegrationCreateWaitListStatusCommand;
use App\Module\Status\DTO\Integration\IntegrationCreateWaitListStatusDTO;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\WaitListStatus;

final class CreateShortcomingWaitListStatusListener
{
    public function handle(CourierShortcomingFilesSavedEvent $event): void
    {
        dispatch(new IntegrationCreateWaitListStatusCommand($this->getDTO($event)));
    }

    public function getDTO(CourierShortcomingFilesSavedEvent $event): IntegrationCreateWaitListStatusDTO
    {
        $DTO = new IntegrationCreateWaitListStatusDTO();
        $DTO->setOrderId($event->orderId);
        $DTO->setCode(RefStatus::CODE_DAMAGE_OR_DIVERGENCE);
        $DTO->setStateId(WaitListStatus::ID_CONFIRMED);
        $DTO->setSource(RequestSource::COURIER_APP->value);
        $DTO->setComment('Создано автоматически после создания Акта сверки');

        return $DTO;
    }
}
