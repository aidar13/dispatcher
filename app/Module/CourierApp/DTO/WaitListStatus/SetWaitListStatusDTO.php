<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\WaitListStatus;

use App\Module\CourierApp\Requests\WaitListStatus\SetWaitListStatusRequest;
use App\Module\Status\Models\StatusSource;
use Illuminate\Support\Facades\Auth;

final class SetWaitListStatusDTO
{
    public int $statusCode;
    public int $sourceId;
    public int $userId;
    public ?string $comment;

    public static function fromRequest(SetWaitListStatusRequest $request): self
    {
        $self             = new self();
        $self->statusCode = (int)$request->input('statusCode');
        $self->sourceId   = (int)$request->input('sourceId') ?: StatusSource::ID_COURIER_APP_V2;
        $self->userId     = (int)Auth::id();
        $self->comment    = $request->input('comment');

        return $self;
    }
}
