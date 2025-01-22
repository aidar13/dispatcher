<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Strategies;

use App\Module\Delivery\Commands\UpdateDeliveryCommand;
use App\Module\Delivery\DTO\DeliveryDTO;
use App\Module\RabbitMQ\Contracts\Strategies\RabbitMQRequestStrategy;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use App\Module\Take\Commands\CreateOrderTakeCommand;
use App\Module\Take\DTO\OrderTakeDTO;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;

class DeliveryUpdatedStrategy implements RabbitMQRequestStrategy
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function isExecutable(string $channel): bool
    {
        return RabbitMQRequest::TYPE_DELIVERY_UPDATED === $channel;
    }

    public function execute(RabbitMQRequest $request): void
    {
        $data = $request->getData();

        try {
            $this->dispatcher->dispatchNow(new UpdateDeliveryCommand(DeliveryDTO::fromEvent($data)));

            event(new RabbitMQRequestStatusEvent(
                $request->id,
                now()
            ));
        } catch (\Throwable $exception) {
            Log::info('Не удалось редактировать доставку в диспетчерской: ', [
                'takeId'  => $data->DTO?->id,
                'message' => $exception->getMessage()
            ]);

            event(new RabbitMQRequestStatusEvent(
                $request->id,
                null,
                $exception->getMessage(),
                now()
            ));
        }
    }
}
