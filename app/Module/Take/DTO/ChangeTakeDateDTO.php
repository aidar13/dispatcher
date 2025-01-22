<?php

declare(strict_types=1);

namespace App\Module\Take\DTO;

use App\Module\Take\Requests\ChangeTakeDateByOrderIdRequest;
use Illuminate\Support\Facades\Auth;

final class ChangeTakeDateDTO
{
    public int $orderId;
    public string $newDate;
    public int $periodId;
    public ?int $userId = null;

    public static function fromRequest(ChangeTakeDateByOrderIdRequest $request): ChangeTakeDateDTO
    {
        $self           = new self();
        $self->orderId  = (int)$request->get('orderId');
        $self->newDate  = $request->get('newDate');
        $self->periodId = (int)$request->get('periodId');
        $self->userId   = (int)$request->get('userId') ?: (int)Auth::id();

        return $self;
    }
}
