<?php

declare(strict_types=1);

namespace App\Module\File\Events\Integration;

use App\Module\File\DTO\Integration\IntegrationCreateSavedFileDTO;
use Ludovicose\TransactionOutbox\Contracts\ShouldBePublish;

final readonly class IntegrationCreateSavedFileEvent implements ShouldBePublish
{
    public function __construct(
        public IntegrationCreateSavedFileDTO $DTO
    ) {
    }

    public function getChannel(): string
    {
        return 'store.saved.file';
    }
}
