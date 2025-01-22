<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Strategies;

use App\Module\RabbitMQ\Contracts\Strategies\RabbitMQRequestStrategy;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use App\Module\Take\Commands\UpdateOrderTakeCommand;
use App\Module\Take\DTO\OrderTakeDTO;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;

class TakeUpdatedStrategy implements RabbitMQRequestStrategy
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function isExecutable(string $channel): bool
    {
        return RabbitMQRequest::TYPE_ORDER_TAKE_UPDATED === $channel;
    }

    public function execute(RabbitMQRequest $request): void
    {
        $data = $request->getData();

        try {
            $this->dispatcher->dispatchNow(new UpdateOrderTakeCommand(OrderTakeDTO::fromEvent($data)));

            event(new RabbitMQRequestStatusEvent(
                $request->id,
                now()
            ));
        } catch (\Throwable $exception) {
            Log::info('Не удалось редактировать забор в диспетчерской: ', [
                'invoiceId' => $data->DTO?->invoiceId,
                'message'   => $exception->getMessage()
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
