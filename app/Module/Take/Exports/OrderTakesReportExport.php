<?php

declare(strict_types=1);

namespace App\Module\Take\Exports;

use App\Module\Status\Models\RefStatus;
use App\Module\Take\Models\OrderTake;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

final class OrderTakesReportExport implements FromView, ShouldAutoSize
{
    private int $numberOfNotTaken               = 0;
    private int $numberOfTaken                  = 0;
    private int $numberOfAssigned               = 0;
    private int $numberOfNotAssigned            = 0;
    private int $numberOfAssignedAndNotTaken    = 0;
    private float $percentOfNotAssigned         = 0;
    private float $percentOfAssigned            = 0;
    private float $percentOfAssigned2           = 0;
    private float $percentOfTaken               = 0;
    private float $percentOfAssignedAndNotTaken = 0;

    public function __construct(
        private readonly Collection $orders
    ) {
    }

    public function view(): View
    {
        set_time_limit(0);

        /** @var View $view */
        $view = view('excel.order-takes', [
            'data'                         => $this->reformData($this->orders),
            'numberOfNotTaken'             => $this->numberOfNotTaken,
            'numberOfTaken'                => $this->numberOfTaken,
            'numberOfAssigned'             => $this->numberOfAssigned,
            'numberOfNotAssigned'          => $this->numberOfNotAssigned,
            'numberOfAssignedAndNotTaken'  => $this->numberOfAssignedAndNotTaken,
            'percentOfNotAssigned'         => $this->percentOfNotAssigned,
            'percentOfAssigned'            => $this->percentOfAssigned,
            'percentOfAssigned2'           => $this->percentOfAssigned2,
            'percentOfTaken'               => $this->percentOfTaken,
            'percentOfAssignedAndNotTaken' => $this->percentOfAssignedAndNotTaken,
        ]);

        return $view;
    }

    private function reformData(Collection $orders): Collection
    {
        return $orders->groupBy('invoice.order_id')
            ->map(function (Collection $orders) {
                $numberOfInvoices       = 0;
                $numberOfTakenInvoices  = 0;
                $numberOfPlaces         = 0;
                $totalWeight            = 0;
                $totalVolumeWeight      = 0;
                $isNotAssigned          = false;
                $isAssigned             = false;
                $isAssignedAndNotTaken  = false;
                $isTaken                = false;
                $isDeliveredToWarehouse = false;
                $status = null;
                $waitListStatus = null;

                /** @var OrderTake $take */
                foreach ($orders as $take) {
                    $numberOfInvoices      += 1;
                    $numberOfTakenInvoices += (int)(bool)$take->takenStatus;
                    $numberOfPlaces        += $take->places;
                    $totalWeight           += $take->weight;
                    $totalVolumeWeight     += $take->volume;

                    $isAssigned             = $isAssigned || $take->getStatusByCode(RefStatus::CODE_ASSIGNED_TO_COURIER);
                    $isNotAssigned          = $isNotAssigned || $take->isStatusNotAssigned();
                    $isTaken                = $isTaken || $take->getStatusByCode(RefStatus::CODE_CARGO_PICKED_UP);
                    $isAssignedAndNotTaken  = $isAssignedAndNotTaken || (!$isTaken && $isAssigned);
                    $isDeliveredToWarehouse = $isDeliveredToWarehouse || $take->getStatusByCode(RefStatus::CODE_CARGO_HANDLING);

                    $status = $status ?? $take->status?->title;
                    $waitListStatus = $waitListStatus ?? $take->waitListStatus?->name;
                }

                $this->numberOfAssigned            += (int)$isAssigned;
                $this->numberOfNotTaken            += (int)!$isTaken;
                $this->numberOfTaken               += (int)$isTaken;
                $this->numberOfNotAssigned         += (int)$isNotAssigned;
                $this->numberOfAssignedAndNotTaken += (int)$isAssignedAndNotTaken;

                $this->percentOfNotAssigned         = ($this->numberOfNotAssigned + $this->numberOfAssigned) > 0 ? round($this->numberOfNotAssigned / ($this->numberOfNotAssigned + $this->numberOfAssigned), 2) * 100 : 0;
                $this->percentOfAssigned            = ($this->numberOfNotAssigned + $this->numberOfAssigned) > 0 ? round($this->numberOfAssigned / ($this->numberOfNotAssigned + $this->numberOfAssigned), 2) * 100 : 0;
                $this->percentOfAssigned2           = ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken) > 0 ? round($this->numberOfAssigned / ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken), 2) * 100 : 0;
                $this->percentOfTaken               = ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken) > 0 ? round($this->numberOfTaken / ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken), 2) * 100 : 0;
                $this->percentOfAssignedAndNotTaken = ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken) > 0 ? round($this->numberOfAssignedAndNotTaken / ($this->numberOfTaken + $this->numberOfAssigned + $this->numberOfAssignedAndNotTaken), 2) * 100 : 0;

                /** @var OrderTake $firstOrderTake */
                $firstOrderTake = $orders->first();

                return [
                    'orderNumber'            => $firstOrderTake->getOrderNumber(),
                    'isNotAssigned'          => $isNotAssigned,
                    'isAssigned'             => $isAssigned,
                    'isAssignedAndNotTaken'  => $isAssignedAndNotTaken,
                    'isTaken'                => $isTaken,
                    'isDeliveredToWarehouse' => $isDeliveredToWarehouse,
                    'sender'                 => $firstOrderTake->order?->sender?->full_name,
                    'courier'                => $firstOrderTake->courier?->full_name,
                    'receiver'               => $firstOrderTake->company?->name,
                    'city'                   => $firstOrderTake->city?->name,
                    'isPickUp'               => $firstOrderTake->order?->sender?->isSelfDelivery(),
                    'orderTakeAddress'       => $firstOrderTake->customer?->address,
                    'sector'                 => $firstOrderTake->customer?->sector?->name,
                    'numberOfInvoices'       => $numberOfInvoices,
                    'period'                 => $firstOrderTake->invoice?->period?->title,
                    'numberOfTakenInvoices'  => $numberOfTakenInvoices,
                    'numberOfPlaces'         => $numberOfPlaces,
                    'totalWeight'            => $totalWeight,
                    'totalVolumeWeight'      => $totalVolumeWeight,
                    'planningTakeDate'       => $firstOrderTake->take_date,
                    'actualTakeDate'         => $firstOrderTake->takenStatus?->created_at,
                    'shipmentType'           => $firstOrderTake->shipmentType?->title,
                    'status'                 => $status,
                    'waitListStatus'         => $waitListStatus
                ];
            })
            ->values();
    }
}
