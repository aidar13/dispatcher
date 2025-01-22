<?php

declare(strict_types=1);

namespace App\Module\Courier\Resources;

use App\Http\Resources\BaseJsonResource;
use App\Module\Courier\Models\CourierLicense;

/**
 * @OA\Schema(
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="courierId", type="integer"),
 *     @OA\Property(property="identifyCardNumber", type="string", example="NUMBER123", description="Номер удостоверения личности"),
 *     @OA\Property(property="identifyCardIssueDate", type="string", example="2024-10-24", description="Дата выдачи удостоверения личности"),
 *     @OA\Property(property="driverLicenseNumber", type="string", example="NUMBER123", description="Номер водительского удостоверения"),
 *     @OA\Property(property="driverLicenseIssueDate", type="string", example="2024-10-24", description="Дата выдачи водительского удостоверения"),
 * )
 *
 * @property CourierLicense $resource
 */
final class CourierLicenseResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->resource->id,
            'courierId'              => $this->resource->courier_id,
            'identifyCardNumber'     => $this->resource->identify_card_number,
            'identifyCardIssueDate'  => $this->resource->identify_card_issue_date,
            'driverLicenseNumber'    => $this->resource->driver_license_number,
            'driverLicenseIssueDate' => $this->resource->driver_license_issue_date,
        ];
    }
}
