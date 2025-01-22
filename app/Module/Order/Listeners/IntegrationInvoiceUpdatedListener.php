<?php

declare(strict_types=1);

namespace App\Module\Order\Listeners;

use App\Module\RabbitMQ\Commands\CreateRabbitMQRequestCommand;
use App\Module\RabbitMQ\DTO\CreateRabbitMQRequestDTO;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;

final class IntegrationInvoiceUpdatedListener
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function handle($event): void
    {
        Log::info('Редактирование накладной в диспетчерской', ['data' => json_encode($event)]);

        $this->dispatcher->dispatchNow(new CreateRabbitMQRequestCommand(
            CreateRabbitMQRequestDTO::fromEvent($event)
        ));
    }
}
