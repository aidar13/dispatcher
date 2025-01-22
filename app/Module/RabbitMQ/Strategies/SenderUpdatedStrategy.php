<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\Strategies;

use App\Module\Order\Commands\UpdateSenderCommand;
use App\Module\Order\DTO\SenderDTO;
use App\Module\RabbitMQ\Contracts\Strategies\RabbitMQRequestStrategy;
use App\Module\RabbitMQ\Events\RabbitMQRequestStatusEvent;
use App\Module\RabbitMQ\Models\RabbitMQRequest;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;

class SenderUpdatedStrategy implements RabbitMQRequestStrategy
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function isExecutable(string $channel): bool
    {
        return RabbitMQRequest::TYPE_SENDER_UPDATED === $channel;
    }

    public function execute(RabbitMQRequest $request): void
    {
        $data = $request->getData();

        try {
            $this->dispatcher->dispatchNow(new UpdateSenderCommand(SenderDTO::fromEvent($data->DTO)));

            event(new RabbitMQRequestStatusEvent(
                $request->id,
                now()
            ));
        } catch (\Throwable $exception) {
            Log::info('Не удалось редактировать отправителя в диспетчерской: ', [
                'senderId' => $data->DTO->id,
                'message'  => $exception->getMessage()
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
