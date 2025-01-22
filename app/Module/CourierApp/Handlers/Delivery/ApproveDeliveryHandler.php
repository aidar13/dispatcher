<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Handlers\Delivery;

use App\Module\CourierApp\Commands\Delivery\ApproveDeliveryCommand;
use App\Module\CourierApp\Events\Delivery\DeliveryStatusChangedEvent;
use App\Module\Delivery\Contracts\Queries\DeliveryQuery;
use App\Module\Delivery\Contracts\Repositories\UpdateDeliveryRepository;
use App\Module\Delivery\Models\Delivery;
use App\Module\File\Commands\CreateFileCommand;
use App\Module\File\DTO\Integration\IntegrationCreateSavedFileDTO;
use App\Module\File\Events\Integration\IntegrationCreateSavedFileEvent;
use App\Module\File\Models\File;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusSource;
use App\Module\Status\Models\StatusType;
use Illuminate\Support\Facades\Log;

final readonly class ApproveDeliveryHandler
{
    public function __construct(
        private DeliveryQuery $query,
        private UpdateDeliveryRepository $repository,
    ) {
    }

    public function handle(ApproveDeliveryCommand $command): void
    {
        $delivery = $this->query->getById($command->deliveryId);

        $delivery->delivery_receiver_name = $command->DTO->deliveryReceiverName;
        $delivery->delivered_at           = $command->DTO->deliveredAt;
        $delivery->status_id              = StatusType::ID_DELIVERED;

        Log::info('Закрытие доставки в deliveries: ' . $delivery->id);
        $this->repository->update($delivery);
        Log::info('Доствка закрыта с id: ' . $delivery->id);

        $this->storeAttachments($command, $delivery);

        event(new DeliveryStatusChangedEvent(
            $delivery->id,
            $command->userId,
            RefStatus::CODE_DELIVERED,
            StatusSource::ID_COURIER_APP_V2
        ));
    }

    private function storeAttachments(ApproveDeliveryCommand $command, Delivery $delivery): void
    {
        foreach ($command->DTO->attachments as $attachment) {
            Log::info(sprintf("Создаем файлы для накладной: %s", $delivery->invoice_number));

            $file = dispatch_sync(new CreateFileCommand(
                $attachment,
                File::TYPE_DELIVERY_APPROVE,
                Delivery::BUCKET_DELIVERY_PATH,
                $attachment->getClientOriginalName(),
                $delivery->invoice_id,
                Invoice::class,
                $command->userId,
            ));

            event(new IntegrationCreateSavedFileEvent(IntegrationCreateSavedFileDTO::fromModel($file)));

            Log::info(sprintf("Файл создан для накладной: %s", $delivery->invoice_number));
        }
    }
}
