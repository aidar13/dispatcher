<?php

declare(strict_types=1);

namespace App\Module\CourierApp\DTO\OrderTake;

use App\Module\CourierApp\Requests\OrderTake\SaveShortcomingFilesRequest;

final class SaveShortcomingFilesDTO
{
    public int $orderId;
    public array $productFiles;
    public array $shortcomingFiles;

    public static function fromRequest(SaveShortcomingFilesRequest $request): self
    {
        $self                   = new self();
        $self->orderId          = (int)$request->get('orderId');
        $self->productFiles     = $request->file('productFiles');
        $self->shortcomingFiles = $request->file('shortcomingFiles');

        return $self;
    }
}
