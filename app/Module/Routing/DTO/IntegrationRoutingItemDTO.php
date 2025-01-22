<?php

declare(strict_types=1);

namespace App\Module\Routing\DTO;

final class IntegrationRoutingItemDTO
{
    public string $carNumber;
    public array|null $route;

    public function __construct()
    {
        $this->route = [];
    }

    public static function fromArray(array $item): self
    {
        $self             = new self();
        $self->carNumber  = $item['vehicle_id'];
        $self->route      = $item['route'];

        return $self;
    }
}
