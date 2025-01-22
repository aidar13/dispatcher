<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Strategies;

use App\Module\Order\Commands\CreateOrderCommand;
use App\Module\Order\DTO\OrderDTO;
use App\Module\RabbitMQ\Contracts\Strategies\RabbitMQRequestStrategy;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;

class OrderCreatedStrategy implements RabbitMQRequestStrategy
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function isExecutable(string $channel): bool
    {
        return RabbitMQRequest::TYPE_ORDER_CREATED === $channel;
    }

    public function execute(RabbitMQRequest $request): void
    {
        $data = $request->getData();

        try {
            $this->dispatcher->dispatchNow(new CreateOrderCommand(OrderDTO::fromEvent($data->DTO)));

            event(new RabbitMQRequestStatusEvent(
                $request->id,
                now()
            ));
        } catch (\Throwable $exception) {
            Log::info('Не удалось создать заказ в диспетчерской: ', [
                'orderId' => $data->DTO->id,
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
