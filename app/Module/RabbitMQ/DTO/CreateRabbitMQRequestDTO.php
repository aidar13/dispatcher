<?php

declare(strict_types=1);

namespace App\Module\RabbitMQ\DTO;

final class CreateRabbitMQRequestDTO
{
    public string $channel;
    public string $data;

    public static function fromEvent($event): self
    {
        $self          = new self();
        $self->channel = $event->channel;
        $self->data    = json_encode($event);

        return $self;
    }
}
