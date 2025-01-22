<?php

declare(strict_types=1);

namespace App\Module\Courier\DTO\Integration;

use App\Module\Courier\Models\CloseCourierDay;

final class CloseCourierDayDTO
{
    public int $id;
    public int $courierId;
    public int $userId;
    public string $date;

    public static function fromModel(CloseCourierDay $model): self
    {
        $self            = new self();
        $self->id        = $model->id;
        $self->courierId = $model->courier_id;
        $self->userId    = $model->user_id;
        $self->date      = $model->date;

        return $self;
    }
}
