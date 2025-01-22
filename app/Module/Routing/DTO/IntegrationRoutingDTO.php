<?php

declare(strict_types=1);

namespace App\Module\Routing\DTO;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

final class IntegrationRoutingDTO
{
    public string $taskId;
    public int|string|null $statusCode;
    public array|null $status;
    public string|null $message;
    public Collection $routes;

    public function __construct()
    {
        $this->routes = collect();
    }

    public static function fromResponse(Response $response): self
    {
        $data   = $response->json();
        $routes = $data['result']['routes'] ?? [];

        $self             = new self();
        $self->statusCode = $response->status();
        $self->status     = $data['status'];
        $self->message    = $data['message'];
        $self->taskId     = $data['id'];

        foreach ($routes as $route) {
            $self->routes->push(IntegrationRoutingItemDTO::fromArray($route));
        }

        return $self;
    }
}
