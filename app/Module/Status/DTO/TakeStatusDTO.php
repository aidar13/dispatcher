<?php

declare(strict_types=1);

namespace App\Module\Status\DTO;

use Illuminate\Support\Carbon;

final class TakeStatusDTO
{
    const STATE_WAITING = 'waiting';
    const STATE_CURRENT = 'current';
    const STATE_DONE = 'done';

    public string $statusName;
    public ?Carbon $date;
    public string $state;

    public function __construct(string $statusName, ?Carbon $date, string $state = self::STATE_WAITING)
    {
        $this->statusName = $statusName;
        $this->date = $date;
        $this->state = $state;
    }

    public function toArray(): array
    {
        $date = is_null($this->date) ? null : $this->date->toDateTimeString();

        return [
            'status' => $this->statusName,
            'date' => $date,
            'state' => $this->state
        ];
    }
}
