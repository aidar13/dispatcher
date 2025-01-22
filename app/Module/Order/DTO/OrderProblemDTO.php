<?php

declare(strict_types=1);

namespace App\Module\Order\DTO;

use App\Module\Order\Models\Order;
use Illuminate\Support\Collection;

final class OrderProblemDTO
{
    public Collection $errors;

    public function __construct(public Order $order)
    {
        $this->errors = collect();
    }
}
