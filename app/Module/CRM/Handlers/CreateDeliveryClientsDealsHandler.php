<?php

declare(strict_types=1);

namespace App\Module\CRM\Handlers;

use App\Module\CRM\Commands\CreateDeliveryClientsDealsCommand;
use App\Module\CRM\Contracts\Repositories\CreateClientAndDealRepository;
use App\Module\CRM\DTO\Integration\CreateClientAndDealDTO;
use App\Module\CRM\Enums\MindSaleCallCenterFunnelStepEnum;
use App\Module\CRM\Enums\MindSaleClientFieldEnum;
use App\Module\CRM\Enums\MindSaleClientSourceEnum;
use App\Module\CRM\Enums\MindSaleDealFieldEnum;
use App\Module\CRM\Enums\MindSaleDealStatusEnum;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Gateway\Contracts\GatewayUserQuery;
use App\Module\Gateway\DTO\GatewayUserDTO;
use App\Module\Gateway\Models\GatewayUser;
use App\Module\Gateway\Models\Role;
use App\Module\Settings\Contracts\Services\SettingsService;
use App\Module\Settings\Models\Settings;
use App\Module\Status\Models\RefStatus;
use Illuminate\Support\Collection;

final readonly class CreateDeliveryClientsDealsHandler
{
    public function __construct(
        private DeliveryQuery $query,
        private CreateClientAndDealRepository $dealRepository,
        private SettingsService $settingsService,
        private GatewayUserQuery $userQuery
    ) {
    }

    public function handle(CreateDeliveryClientsDealsCommand $command): void
    {
        if (
            !$this->settingsService->isEnabled(Settings::CRM_MINDSALE) &&
            !$this->userIsCourier($command->userId)
        ) {
            return;
        }

        $deliveryInfo = $this->query->getById($command->deliveryId);
        /** @var GatewayUser $manager */
        $manager = ($this->getUser($deliveryInfo->company->manager_id))->first();
        $courier = $deliveryInfo->courier;


        $dto = new CreateClientAndDealDTO();
        $dto->setClientSourceId(MindSaleClientSourceEnum::SPARK_SITE->value);
        $dto->setPhones([
            $deliveryInfo->customer->phone
        ]);
        $dto->pushClientField(MindSaleClientFieldEnum::COMPANY_NAME->value, $deliveryInfo->company->name);
        $dto->pushClientField(MindSaleClientFieldEnum::MANAGER_FULL_NAME->value, $manager->name);
        $dto->pushDeal([
            'dealFunnelStepId' => MindSaleCallCenterFunnelStepEnum::WAITING_LIST->value,
            'dealStatusId'     => MindSaleDealStatusEnum::RAW->value,
            'dealFields'       => [
                [
                    'id'    => MindSaleDealFieldEnum::COMMENT->value,
                    'value' => __('Курьер :fullName изменил статус доставки(:invoiceNumber) на :statusName', [
                        'fullName'      => $courier?->full_name,
                        'invoiceNumber' => $deliveryInfo->invoice_number,
                        'statusName'    => RefStatus::getNameByCode($command->statusCode)
                    ])
                ],
                [
                    'id'    => MindSaleDealFieldEnum::ORDER_INVOICE_NUMBER->value,
                    'value' => $deliveryInfo->invoice_number
                ],
                [
                    'id'    => MindSaleDealFieldEnum::COURIER_FULL_NAME->value,
                    'value' => $courier?->full_name
                ],
                [
                    'id'    => MindSaleDealFieldEnum::CLIENT_FULL_NAME->value,
                    'value' => $deliveryInfo->customer->full_name
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
