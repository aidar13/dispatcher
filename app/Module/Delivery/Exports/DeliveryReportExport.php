<?php

declare(strict_types=1);

namespace App\Module\Delivery\Exports;

use App\Module\Delivery\Models\Delivery;
use App\Module\Status\Models\RefStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

final class DeliveryReportExport implements FromView, ShouldAutoSize
{
    private int $numberOfInProgressStatuses = 0;
    private int $numberOfDeliveredStatuses  = 0;
    private int $numberOfCancelledStatuses  = 0;

    public function __construct(
        private readonly Collection $deliveries
    ) {
    }

    public function view(): View
    {
        set_time_limit(0);

        /** @var View $view */
        $view = view('excel.deliveries', [
            'deliveries'                 => $this->reformData($this->deliveries),
            'numberOfInProgressStatuses' => $this->numberOfInProgressStatuses,
            'numberOfDeliveredStatuses'  => $this->numberOfDeliveredStatuses,
            'numberOfCancelledStatuses'  => $this->numberOfCancelledStatuses,
            'totalNumberOfStatuses'      => $this->numberOfCancelledStatuses + $this->numberOfInProgressStatuses + $this->numberOfDeliveredStatuses
        ]);

        return $view;
    }

    private function reformData(Collection $deliveries): Collection
    {
        return $deliveries->map(function (Delivery $delivery) {
            $invoice          = $delivery->invoice;
            $cargoArrivedCity = $invoice->getStatusByCode(RefStatus::CODE_CARGO_ARRIVED_CITY);
            $cancelledStatus  = $invoice->getStatusByCode(RefStatus::CODE_ORDER_CANCELLED);

            $isDelivered  = $delivery->isDelivered();
            $isCancelled  = $delivery->isReturned();
            $isInProgress = !($isCancelled || $isDelivered);

            $this->numberOfDeliveredStatuses  += (int)$isDelivered;
            $this->numberOfCancelledStatuses  += (int)$isCancelled;
            $this->numberOfInProgressStatuses += (int)$isInProgress;

            $returnedInvoiceNumber = '';
            if ($cancelledStatus && $delivery->invoice->order->cancelledOrder) {
                $returnedInvoiceNumber = $delivery->invoice_number . 'V';
            }

            return [
                'invoiceNumber'             => $delivery->invoice_number,
                'isInProgress'              => $isInProgress,
                'isDelivered'               => $isDelivered,
                'isCancelled'               => $isCancelled,
                'hasCodeCargoAwaitShipment' => $invoice->isCodeInStatuses(RefStatus::CODE_CARGO_AWAIT_SHIPMENT),
                'hasCodeDeliveryInProgress' => $invoice->isCodeInStatuses(RefStatus::CODE_DELIVERY_IN_PROGRESS),
                'hasCodeCargoArrivedCity'   => (bool)$cargoArrivedCity,
                'hasCodeCargoReturned'      => $invoice->isCodeInStatuses(RefStatus::CODE_CARGO_RETURNED),
                'hasCodeOrderCancelled'     => $invoice->isCodeInStatuses(RefStatus::CODE_ORDER_CANCELLED),
                'companyName'               => $delivery->company?->name,
                'courierFullName'           => $delivery->courier?->full_name,
                'cityName'                  => $delivery->city?->name,
                'sector'                    => $delivery->customer?->sector?->name,
                'places'                    => $delivery->places,
                'weight'                    => $delivery->weight,
                'volumeWeight'              => $delivery->volume_weight,
                'receiverFullAddress'       => $delivery->customer->address,
                'isPickUp'                  => $delivery->invoice->receiver?->isPickup(),
                'receiverFullName'          => $delivery->delivery_receiver_name,
                'shipmentType'              => $delivery->invoice->shipmentType?->title,
                'invoiceCreatedAt'          => $delivery->invoice->created_at->toDateTimeString(),
                'deliveryCreatedAt'         => $delivery->created_at->toDateTimeString(),
                'cargoArrivedCityDate'      => $cargoArrivedCity?->created_at?->toDateTimeString(),
                'deliveredAt'               => $delivery->delivered_at,
                'status'                    => $delivery->status->title,
                'returnedInvoiceNumber'     => $returnedInvoiceNumber,
                'cancelledAt'               => $cancelledStatus?->created_at?->toDateTimeString(),
                'cancellationReason'        => $cancelledStatus?->comment,
                'waitListStatus'            => $delivery->waitListStatus?->name,
                'waitListConfirmed'         => $invoice->getWaitListConfirmed(),
                'waitListNotConfirmed'      => $invoice->getWaitListNotConfirmed()
            ];
        });
    }
}
