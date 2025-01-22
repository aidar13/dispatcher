<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\Delivery;

use App\Module\CourierApp\DTO\Delivery\ApproveDeliveryViaVerificationDTO;

final readonly class ApproveDeliveryByInvoiceIdCommand
{
    public function __construct(
        public ApproveDeliveryViaVerificationDTO $DTO
    ) {
    }
}
