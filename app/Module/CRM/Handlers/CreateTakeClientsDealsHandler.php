<?php

declare(strict_types=1);

namespace App\Module\CRM\Handlers;

use App\Module\CRM\Commands\CreateTakeClientsDealsCommand;
use App\Module\CRM\Contracts\Repositories\CreateClientAndDealRepository;
use App\Module\CRM\DTO\Integration\CreateClientAndDealDTO;
use App\Module\CRM\Enums\MindSaleCallCenterFunnelStepEnum;
use App\Module\CRM\Enums\MindSaleClientFieldEnum;
use App\Module\CRM\Enums\MindSaleClientSourceEnum;
use App\Module\CRM\Enums\MindSaleDealFieldEnum;
use App\Module\CRM\Enums\MindSaleDealStatusEnum;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\GatewayUser;
use App\Module\Gateway\Models\Role;
use App\Module\Order\Contracts\Queries\OrderQuery;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;
use App\Module\Status\Models\RefStatus;
use App\Module\Take\Models\OrderTake;
use Illuminate\Support\Collection;

final readonly class CreateTakeClientsDealsHandler
{
    public function __construct(
        private OrderQuery $orderQuery,
        private CreateClientAndDealRepository $dealRepository,
        private SettingsService $settingsService,
        private GatewayUserQuery $userQuery
    ) {
    }

    public function handle(CreateTakeClientsDealsCommand $command): void
    {
        if (
            !$this->settingsService->isEnabled(Settings::CRM_MINDSALE) &&
            !$this->userIsCourier($command->userId)
        ) {
            return;
        }

        $order = $this->orderQuery->getById($command->orderId);

        /** @var OrderTake $orderTake */
        $orderTake = $order?->orderTakes->first();
        $courier   = $orderTake->courier;
        $sender    = $order?->sender;

        /** @var GatewayUser $manager */
        $manager = ($this->getUser($orderTake->company->manager_id))->first();

        $dto = new CreateClientAndDealDTO();
        $dto->setClientSourceId(MindSaleClientSourceEnum::SPARK_SITE->value);
        $dto->setPhones([
            $sender?->phone
        ]);
        $dto->pushClientField(MindSaleClientFieldEnum::COMPANY_NAME->value, $orderTake->company->name);
        $dto->pushClientField(MindSaleClientFieldEnum::MANAGER_FULL_NAME->value, $manager->name);
        $dto->pushDeal([
            'dealFunnelStepId' => MindSaleCallCenterFunnelStepEnum::WAITING_LIST->value,
            'dealStatusId'     => MindSaleDealStatusEnum::RAW->value,
            'dealFields'       => [
                [
                    'id'    => MindSaleDealFieldEnum::COMMENT->value,
                    'value' => __('Курьер :fullName изменил статус забора(:orderNumber) на :statusName', [
                        'fullName'    => $courier?->full_name,
                        'orderNumber' => $order?->number,
                        'statusName'  => RefStatus::getNameByCode($command->statusCode)
                    ])
                ],
                [
                    'id'    => MindSaleDealFieldEnum::ORDER_INVOICE_NUMBER->value,
                    'value' => $order?->number
                ],
                [
                    'id'    => MindSaleDealFieldEnum::COURIER_FULL_NAME->value,
                    'value' => $courier?->full_name
                ],
                [
                    'id'    => MindSaleDealFieldEnum::CLIENT_FULL_NAME->value,
                    'value' => $sender?->full_name
                ],
                [
                    'id'    => MindSaleDealFieldEnum::RECEIPT_DATE->value,
                    'value' => now()->toDateTimeString()
                ],
            ]
        ]);

        $this->dealRepository->createClientsDeals($dto);
    }

    private function userIsCourier(int $userId): bool
    {
        $DTO = new GatewayUserDto();
        $DTO->setRoleId(Role::ID_COURIER);
        $DTO->setIds([$userId]);

        return (bool)$this->userQuery->getUsersWithFilter($DTO)?->contains('id', $userId);
    }

    public function getUser(int $id): ?Collection
    {
        $DTO = new GatewayUserDTO();
        $DTO->setIds([$id]);

        return $this->userQuery->getUsersWithFilter($DTO);
    }
}
