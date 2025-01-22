<?php

declare(strict_types=1);

namespace App\Module\Courier\Services;

use App\Helpers\DateHelper;
use App\Module\Courier\Contracts\Queries\CourierReportQuery;
use App\Module\Courier\Contracts\Services\CourierReportService as CourierEndOfDayServiceContract;
use App\Module\Courier\DTO\CourierReportDTO;
use App\Module\Courier\Models\Courier;
use App\Module\CourierApp\Models\CourierPayment;
use App\Module\Delivery\Models\Delivery;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use App\Module\Status\Resources\RefStatusResource;
use App\Module\Status\Resources\WaitListStatusResource;
use App\Module\Take\Models\OrderTake;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

final class CourierReportService implements CourierEndOfDayServiceContract
{
    private SupportCollection $minDate;
    private SupportCollection $maxDate;
    private SupportCollection $waves;
    private SupportCollection $cashes;
    private SupportCollection $codPayment;
    private SupportCollection $detailsOfOrders;
    private SupportCollection $returnDelivery;
    private SupportCollection $cancelledTakes;
    private bool $hasReturnDelivery;

    public function __construct(
        private readonly CourierReportQuery $query,
    ) {
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function getCourierEndOfDayPaginated(CourierReportDTO $DTO): LengthAwarePaginator
    {
        $couriers = $this->query->getCourierEndOfDayPaginated($DTO);

        $fromDate = Carbon::parse($DTO->fromDate);
        $toDate   = Carbon::parse($DTO->toDate)->subDay();
        $dates    = $fromDate->daysUntil($toDate)->toArray();

        $dates = collect($dates)->map(function ($date) {
            return DateHelper::getDate($date);
        });

        /** @var Courier $courier */
        foreach ($couriers as $courier) {
            $info = collect();

            foreach ($dates as $date) {
                $this->minDate           = collect();
                $this->maxDate           = collect();
                $this->waves             = collect();
                $this->cashes            = collect();
                $this->codPayment        = collect();
                $this->hasReturnDelivery = false;

                $takes = $courier->takes->filter(function (OrderTake $take) use ($date) {
                    return $this->hasStatusByCodeAndDate($take->statuses, RefStatus::CODE_CARGO_PICKED_UP, $date);
                });

                $deliveries = $courier->deliveries->filter(function (Delivery $delivery) use ($date) {
                    return $this->hasStatusByCodeAndDate($delivery->statuses, RefStatus::CODE_DELIVERY_IN_PROGRESS, $date);
                });

                if ($takes->isNotEmpty()) {
                    $courier->takes->each(function (OrderTake $take) use ($date) {
                        $this->minDate->push($this->getTime($take->statuses->where('code', RefStatus::CODE_CARGO_PICKED_UP)->filter(function (OrderStatus $status) use ($date) {
                            return $this->isDateEqualToStatusCreatedAt($status, $date);
                        })));

                        $this->maxDate->push($this->getTime($take->statuses->where('code', RefStatus::CODE_CARGO_HANDLING)->filter(function (OrderStatus $status) use ($date) {
                            return $this->isDateEqualToStatusCreatedAt($status, $date);
                        })));

                        $this->waves->push($take->invoice->wave?->title);

                        if ($take->invoice->hasSenderPaymentType() && $take->invoice->hasCashPaymentMethod()) {
                            if ($take->statuses->where('code', RefStatus::CODE_CARGO_HANDLING)) {
                                $this->cashes->push($take->invoice->cash_sum);
                            }
                        }
                    });
                }

                if ($deliveries->isNotEmpty()) {
                    $courier->deliveries->each(function (Delivery $delivery) use ($date) {
                        $this->minDate->push($this->getTime($delivery->statuses->where('code', RefStatus::CODE_DELIVERY_IN_PROGRESS)->filter(function (OrderStatus $status) use ($date) {
                            return $this->isDateEqualToStatusCreatedAt($status, $date);
                        })));

                        $this->maxDate->push($this->getTime($delivery->statuses->where('code', RefStatus::CODE_DELIVERED)->filter(function (OrderStatus $status) use ($date) {
                            return $this->isDateEqualToStatusCreatedAt($status, $date);
                        })));

                        $this->waves->push($delivery->invoice?->wave?->title);

                        if ($delivery->statuses->where('code', RefStatus::CODE_DELIVERED)) {
                            $this->cashes->push($delivery->invoice?->cash_sum);
                            $this->codPayment->push($delivery->invoice?->cargo?->cod_payment);
                        }

                        $this->hasReturnDelivery = $delivery->statuses->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY)->isNotEmpty();
                    });
                }

                $interval = $this->getIntervalTime($this->minDate, $this->maxDate);

                if ($takes->isNotEmpty() || $deliveries->isNotEmpty()) {
                    $info->push([
                        'courierId'           => $courier->id,
                        'date'                => $date,
                        'takesTotal'          => $takes->count(),
                        'takesShipped'        => $takes->where('status_id', StatusType::ID_CARGO_HANDLING)->count(),
                        'deliveriesTotal'     => $deliveries->count(),
                        'deliveriesDelivered' => $deliveries->where('status_id', StatusType::ID_DELIVERED)->count(),
                        'timeOfWork'          => "$interval->h ч $interval->i м",
                        'waves'               => $this->waves->unique()->values()->all(),
                        'cash'                => $this->cashes->sum(),
                        'codPayment'          => $this->codPayment->sum(),
                        'hasReturnDelivery'   => $this->hasReturnDelivery,
                        'isClosed'            => $courier->closeCourierDays->where('date', $date)->isNotEmpty(),
                    ]);
                }
            }

            $courier->info = $info;
        }

        return $couriers;
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function getCourierEndOfDay(int $courierId, string $date): Courier
    {
        $courier = $this->query->getCourierEndOfDay($courierId, $date);

        $info                    = collect();
        $this->minDate           = collect();
        $this->maxDate           = collect();
        $this->waves             = collect();
        $this->cashes            = collect();
        $this->codPayment        = collect();
        $this->returnDelivery    = collect();
        $this->detailsOfOrders   = collect();
        $this->cancelledTakes    = collect();
        $this->hasReturnDelivery = false;

        $takes = $courier->takes->filter(function (OrderTake $take) use ($date) {
            return $this->hasStatusByCodeAndDate($take->statuses, RefStatus::CODE_CARGO_PICKED_UP, $date);
        });

        $deliveries = $courier->deliveries->filter(function (Delivery $delivery) use ($date) {
            return $this->hasStatusByCodeAndDate($delivery->statuses, RefStatus::CODE_DELIVERY_IN_PROGRESS, $date);
        });

        if ($takes->isNotEmpty()) {
            $courier->takes->each(function (OrderTake $take) use ($date) {
                $this->minDate->push($this->getTime($take->statuses->where('code', RefStatus::CODE_CARGO_PICKED_UP)->filter(function (OrderStatus $status) use ($date) {
                    return $this->isDateEqualToStatusCreatedAt($status, $date);
                })));

                $this->maxDate->push($this->getTime($take->statuses->where('code', RefStatus::CODE_CARGO_HANDLING)->filter(function (OrderStatus $status) use ($date) {
                    return $this->isDateEqualToStatusCreatedAt($status, $date);
                })));

                if ($take->invoice->hasSenderPaymentType() && $take->invoice->hasCashPaymentMethod()) {
                    if ($take->statuses->where('code', RefStatus::CODE_CARGO_HANDLING)) {
                        $cash = $take->invoice->cash_sum;
                        $this->cashes->push($cash);
                    }
                }

                if ($take->statuses->whereIn('code', [RefStatus::CODE_PICKUP_CANCELED, RefStatus::CODE_CANCEL_RECEIVE])->isNotEmpty()) {
                    $this->cancelledTakes->push(
                        $take->statuses->whereIn('code', [RefStatus::CODE_PICKUP_CANCELED, RefStatus::CODE_CANCEL_RECEIVE])
                    );
                }

                $this->detailsOfOrders->push([
                    'wave'             => $take->invoice->wave?->title,
                    'routeSheet'       => null,
                    'isTake'           => true,
                    'isDelivery'       => false,
                    'orderNumber'      => $take->order->number,
                    'weight'           => $take->invoice->cargo->weight,
                    'invoiceNumber'    => $take->invoice->invoice_number,
                    'status'           => new RefStatusResource($take->invoice->status),
                    'address'          => $take->order->sender->full_address,
                    'waitList'         => $take->order?->waitListStatuses->isNotEmpty()
                        ? new WaitListStatusResource($take->order?->waitListStatuses->last())
                        : null,
                    'cash'             => $cash ?? 0,
                    'codPayment'       => 0,
                    'payerCompanyName' => $take->invoice->payerCompany->short_name ?? $take->order->company->short_name ?? null,
                ]);
            });
        }

        if ($deliveries->isNotEmpty()) {
            $courier->deliveries->each(function (Delivery $delivery) use ($date) {
                $this->minDate->push($this->getTime($delivery->statuses->where('code', RefStatus::CODE_DELIVERY_IN_PROGRESS)->filter(function (OrderStatus $status) use ($date) {
                    return $this->isDateEqualToStatusCreatedAt($status, $date);
                })));

                $this->maxDate->push($this->getTime($delivery->statuses->where('code', RefStatus::CODE_DELIVERED)->filter(function (OrderStatus $status) use ($date) {
                    return $this->isDateEqualToStatusCreatedAt($status, $date);
                })));

                if ($delivery->statuses->where('code', RefStatus::CODE_DELIVERED)) {
                    $cash       = $delivery->invoice->cash_sum;
                    $codPayment = $delivery->invoice->cargo->cod_payment;
                    $this->cashes->push($cash);
                    $this->codPayment->push($codPayment);
                }

                if ($delivery->statuses->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY)->isNotEmpty()) {
                    $this->returnDelivery->push($delivery->statuses->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY));
                }

                $this->detailsOfOrders->push([
                    'wave'             => $delivery->invoice->wave?->title,
                    'routeSheet'       => $delivery->routeSheetInvoice?->routeSheet?->number,
                    'isTake'           => false,
                    'isDelivery'       => true,
                    'orderNumber'      => $delivery->invoice->order->number,
                    'weight'           => $delivery->invoice->cargo->weight,
                    'invoiceNumber'    => $delivery->invoice->invoice_number,
                    'address'          => $delivery->invoice?->receiver?->full_address,
                    'status'           => new RefStatusResource($delivery->invoice->status),
                    'waitList'         => $delivery->invoice?->waitListStatuses->isNotEmpty()
                        ? new WaitListStatusResource($delivery->invoice?->waitListStatuses->last())
                        : null,
                    'cash'             => $cash ?? 0,
                    'codPayment'       => $codPayment ?? 0,
                    'payerCompanyName' => $delivery->invoice->payerCompany->short_name ?? $delivery->invoice->order->company->short_name ?? null,
                ]);
            });
        }

        $interval = $this->getIntervalTime($this->minDate, $this->maxDate);

        if ($takes->isNotEmpty() || $deliveries->isNotEmpty()) {
            $info = [
                'date'                => $date,
                'takesTotal'          => $takes->count(),
                'takesShipped'        => $takes->where('status_id', StatusType::ID_CARGO_HANDLING)->count(),
                'deliveriesTotal'     => $deliveries->count(),
                'deliveriesDelivered' => $deliveries->where('status_id', StatusType::ID_DELIVERED)->count(),
                'returnDeliveryCount' => $this->returnDelivery->count(),
                'cancelledTakes'      => $this->cancelledTakes->count(),
                'timeOfWork'          => "$interval->h ч $interval->i м",
                'cash'                => $this->cashes->sum(),
                'codPayment'          => $this->codPayment->sum(),
                'hasReturnDelivery'   => $this->hasReturnDelivery,
                'isClosed'            => $courier->closeCourierDays->where('date', $date)->isNotEmpty(),
                'costForRoad'         => CourierPayment::getSumCostForRoad($courier->courierPayments),
                'costForParking'      => CourierPayment::getSumCostForParking($courier->courierPayments),
                'detailsOfOrders'     => $this->detailsOfOrders,
            ];
        }

        $courier->info = $info;

        return $courier;
    }

    public function hasStatusByCodeAndDate(Collection $statuses, int $code, string $date): bool
    {
        return $statuses->where('code', $code)->first()?->created_at->format('Y-m-d') === $date;
    }

    private function getTime(Collection $statuses): ?string
    {
        $dates = $statuses->pluck('created_at')->each(function (Carbon $date) {
            return DateHelper::getDate($date);
        });

        return DateHelper::getDateWithTime($dates->min());
    }

    private function getIntervalTime($min, $max): ?DateInterval
    {
        $minDate = Carbon::parse($min->min());
        $maxDate = Carbon::parse($max->max());

        return $minDate->diff($maxDate);
    }

    private function isDateEqualToStatusCreatedAt(OrderStatus $status, $date): bool
    {
        return $status->created_at->format('Y-m-d') === $date;
    }
}
