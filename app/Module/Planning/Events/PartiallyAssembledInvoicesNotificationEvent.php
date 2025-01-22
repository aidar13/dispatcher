<?php

declare(strict_types=1);

namespace App\Module\Planning\Events;

use Illuminate\Support\Collection;

final class PartiallyAssembledInvoicesNotificationEvent
{
    public function __construct(
        public readonly Collection $partiallyAssembledInvoicesCollection,
        public readonly int $containerId,
    ) {
    }
}
