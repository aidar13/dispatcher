<?php

declare(strict_types=1);

namespace App\Module\CourierApp\Commands\CourierPayment;

use App\Module\CourierApp\DTO\CourierPayment\SaveDeliveryCourierPaymentFilesDTO;
use App\Module\CourierApp\DTO\CourierPayment\SaveOrderTakeCourierPaymentFilesDTO;

final class SaveCourierPaymentFilesCommand
{
    public function __construct(
        public readonly int $userId,
        public readonly SaveOrderTakeCourierPaymentFilesDTO|SaveDeliveryCourierPaymentFilesDTO $DTO,
    ) {
    }
}
