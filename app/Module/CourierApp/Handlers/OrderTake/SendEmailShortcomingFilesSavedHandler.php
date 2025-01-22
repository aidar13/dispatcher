<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\OrderTake;

use App\Module\CourierApp\Commands\OrderTake\SendEmailShortcomingFilesSavedCommand;
use App\Module\DispatcherSector\Contracts\Queries\DispatchersSectorUserQuery;
use App\Module\DispatcherSector\Contracts\Services\GetUserEmailService;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Notification\Contracts\Repositories\SendEmailNotificationRepository;
use App\Module\Notification\DTO\EmailNotificationDTO;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Order\Models\Order;
use Illuminate\Support\Collection;

final class SendEmailShortcomingFilesSavedHandler
{
    private string $uri;

    public function __construct(
        private readonly OrderQuery $query,
        private readonly SendEmailNotificationRepository $emailRepository,
        private readonly DispatchersSectorUserQuery $userQuery,
        private readonly GetUserEmailService $service,
        private readonly GatewayUserQuery $gatewayUserQuery
    ) {
        $this->uri = config('urls.bpms');
    }

    public function handle(SendEmailShortcomingFilesSavedCommand $command): void
    {
        $dto = $this->getDTO($command->orderId);
        $this->emailRepository->send($dto);
    }


    private function getDTO(int $orderId): EmailNotificationDTO
    {
        $order = $this->query->getById($orderId);

        $dto = new EmailNotificationDTO();
        $dto->setSubject(__('email.courier_app.shortcoming.created.subject'));
        $dto->setContent(__('email.courier_app.shortcoming.created.content', [
            'orderNumber' => $order?->number,
            'orderId'     => $order?->id,
            'uri'         => $this->uri,
        ]));

        $dto->setEmails($this->getEmails($order));

        return $dto;
    }

    private function getEmails(Order $order): array
    {
        $dcUsers = $this->getDispatcherSectorUsers($order->sender->city_id);
        $emails  = $dcUsers->pluck('email');
        $emails->push(config('emails.courier_shortcoming'));
        $emails->push($this->getUserById($order->company->manager_id)?->first()?->email);

        return $emails->toArray();
    }

    private function getDispatcherSectorUsers(int $whereCityId): Collection
    {
        $userIds = ($this->userQuery->getByCiyId($whereCityId))->pluck('user_id')->toArray();

        return $this->service->getDispatchers($userIds);
    }

    private function getUserById(int $id): ?Collection
    {
        $DTO = new GatewayUserDto();
        $DTO->setIds([$id]);

        return $this->gatewayUserQuery->getUsersWithFilter($DTO);
    }
}
