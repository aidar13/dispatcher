<?php

declare(strict_types=1);

namespace App\Module\Courier\Queries;

use App\Module\Courier\Contracts\Queries\CourierReportQuery as CourierEndOfDayQueryContract;
use App\Module\Courier\DTO\CourierReportDTO;
use App\Module\Courier\Models\Courier;
use App\Module\Order\Models\Invoice;
use App\Module\Status\Models\RefStatus;
use App\Module\Status\Models\StatusType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

final class CourierReportQuery implements CourierEndOfDayQueryContract
{
    public function getCourierEndOfDayPaginated(CourierReportDTO $DTO): LengthAwarePaginator
    {
        return Courier::query()
            ->select(['id', 'full_name', 'user_id', 'dispatcher_sector_id', 'car_id'])
            ->with([
                'takes' => function ($query) use ($DTO) {
                    $query->whereHas('statuses', function ($subQuery) use ($DTO) {
                        $subQuery
                            ->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING])
                            ->whereBetween('created_at', [$DTO->fromDate, $DTO->toDate]);
                    })
                        ->with([
                        'statuses:id,order_id,invoice_id,code,created_at',
                        'invoice' => function ($query) use ($DTO) {
                            $query->select('id', 'wave_id', 'cash_sum', 'payment_type', 'payment_method')
                                ->when($DTO->hasCash == 1, fn(Builder $q) => $q->where('cash_sum', '<=', '0.00'))
                                ->when($DTO->hasCash == 2, fn(Builder $q) => $q->where('cash_sum', '>', '0.00'));
                        },
                        'invoice.wave' => function ($query) {
                            $query->select('id', 'title');
                        },
                        'invoice.cargo' => function ($query) use ($DTO) {
                            $query->select('id', 'invoice_id', 'cod_payment')
                                ->when($DTO->hasCodPayment == 1, fn(Builder $q) => $q->where('cod_payment', '<=', 0))
                                ->when($DTO->hasCodPayment == 2, fn(Builder $q) => $q->where('cod_payment', '>', 0));
                        },
                    ]);
                },
                'deliveries' => function ($query) use ($DTO) {
                        $query->whereHas('statuses', function ($subQuery) use ($DTO) {
                            $subQuery
                                ->whereIn('code', [RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED])
                                ->whereBetween('created_at', [$DTO->fromDate, $DTO->toDate]);
                        })
                            ->when($DTO->hasReturn == 1, fn(Builder $subQuery) => $subQuery->where('status_id', '!=', StatusType::ID_CARGO_RETURNED))
                            ->when($DTO->hasReturn == 2, fn(Builder $subQuery) => $subQuery->where('status_id', StatusType::ID_CARGO_RETURNED))
                            ->with([
                                'statuses:id,order_id,invoice_id,code,created_at',
                                'invoice' => function ($query) use ($DTO) {
                                    $query->select('id', 'wave_id', 'cash_sum', 'payment_type', 'payment_method')
                                        ->when($DTO->hasCash == 1, fn(Builder $q) => $q->where('cash_sum', '<=', '0.00'))
                                        ->when($DTO->hasCash == 2, fn(Builder $q) => $q->where('cash_sum', '>', '0.00'));
                                },
                                'invoice.wave' => function ($query) {
                                    $query->select('id', 'title');
                                },
                                'invoice.cargo:id,invoice_id,cod_payment,weight'
                            ]);
                },
                'closeCourierDays' => function ($query) use ($DTO) {
                    $query->whereBetween('date', [$DTO->fromDate, $DTO->toDate]);
                }
            ])
            ->where(function (Builder $query) use ($DTO) {
                $query
                    ->whereHas('takes', function (Builder $query) use ($DTO) {
                        $query->whereHas('statuses', function (Builder $subQuery) use ($DTO) {
                            $subQuery
                                ->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING])
                                ->whereBetween('created_at', [$DTO->fromDate, $DTO->toDate]);
                        })
                        ->whereHas('invoice', function (Builder $subQuery) use ($DTO) {
                            $subQuery
                                ->when($DTO->hasCash == 1, fn(Builder $q) => $q->where('cash_sum', '<=', '0.00'))
                                ->when($DTO->hasCash == 2, fn(Builder $q) => $q->where('cash_sum', '>', '0.00'));
                        })
                        ->whereHas('invoice.cargo', function (Builder $subQuery) use ($DTO) {
                            $subQuery
                                ->when($DTO->hasCodPayment == 1, fn(Builder $q) => $q->where('cod_payment', '<=', 0))
                                ->when($DTO->hasCodPayment == 2, fn(Builder $q) => $q->where('cod_payment', '>', 0));
                        });
                    })
                    ->orWhereHas('deliveries', function (Builder $query) use ($DTO) {
                        $query->whereHas('statuses', function ($subQuery) use ($DTO) {
                            $subQuery
                                ->whereIn('code', [RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED, RefStatus::CODE_COURIER_RETURN_DELIVERY])
                                ->whereBetween('created_at', [$DTO->fromDate, $DTO->toDate]);
                        })
                            ->whereHas('invoice', function (Builder $subQuery) use ($DTO) {
                                $subQuery
                                    ->when($DTO->hasCash == 1, fn(Builder $q) => $q->where('cash_sum', '<=', '0.00'))
                                    ->when($DTO->hasCash == 2, fn(Builder $q) => $q->where('cash_sum', '>', '0.00'));
                            })
                            ->whereHas('invoice.cargo', function (Builder $subQuery) use ($DTO) {
                                $subQuery
                                    ->when($DTO->hasCodPayment == 1, fn(Builder $q) => $q->where('cod_payment', '<=', 0))
                                    ->when($DTO->hasCodPayment == 2, fn(Builder $q) => $q->where('cod_payment', '>', 0));
                            })
                            ->when($DTO->hasReturn == 1, fn(Builder $subQuery) => $subQuery->where('status_id', '!=', StatusType::ID_CARGO_RETURNED))
                            ->when($DTO->hasReturn == 2, fn(Builder $subQuery) => $subQuery->where('status_id', StatusType::ID_CARGO_RETURNED));
                    });
            })
            ->when($DTO->courierId, fn(Builder $query) => $query->where('id', $DTO->courierId))
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->where('dispatcher_sector_id', $DTO->dispatcherSectorId))
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    /**
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     * @param int $courierId
     * @param string $date
     * @return Collection|Invoice
     */
    public function getCloseDayReportCourierTakesAndDeliveries(int $courierId, string $date): Collection|Invoice
    {
        return Invoice::query()
            ->with([
                'statuses',
                'take' => function ($query) use ($courierId, $date) {
                    $query->where('courier_id', $courierId)
                        ->whereDate('take_date', $date)
                        ->where(function (Builder $query) {
                            $query->newQuery()
                                ->whereHas('invoice', fn(Builder $query) => $query->whereNull('invoice_number'))
                                ->orWhereDoesntHave(
                                    'invoice.statuses',
                                    fn (Builder $query) => $query->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_PICKUP_CANCELED])
                                );
                        });
                },
                'deliveries' => function ($query) use ($courierId, $date) {
                    $query->where('courier_id', $courierId)
                        ->whereDate('created_at', $date)
                        ->whereDoesntHave('invoice.statuses', function (Builder $query) {
                            $query->newQuery()
                                ->whereIn('code', [RefStatus::CODE_DELIVERED, RefStatus::CODE_DELIVERY_CANCELED]);
                        });
                }
            ])
            ->whereHas('take', function (Builder $query) use ($courierId, $date) {
                $query->where('courier_id', $courierId)
                    ->whereDate('take_date', $date)
                    ->where(function (Builder $query) {
                        $query->newQuery()
                            ->whereHas('invoice', fn(Builder $query) => $query->whereNull('invoice_number'))
                            ->orWhereDoesntHave(
                                'invoice.statuses',
                                fn (Builder $query) => $query->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_PICKUP_CANCELED])
                            );
                    });
            })
            ->orWhereHas('deliveries', function (Builder $query) use ($courierId, $date) {
                $query->where('courier_id', $courierId)
                    ->whereDate('created_at', $date)
                    ->whereDate('created_at', $date)
                    ->whereDoesntHave('invoice.statuses', function (Builder $query) {
                        $query->newQuery()
                            ->whereIn('code', [RefStatus::CODE_DELIVERED, RefStatus::CODE_DELIVERY_CANCELED]);
                    });
            })->get();
    }

    /**
     * @param int $courierId
     * @param string $date
     * @return Courier
     */
    public function getCourierEndOfDay(int $courierId, string $date): Courier
    {
        return Courier::
            with([
                'takes' => function ($query) use ($date) {
                    $query->whereHas('statuses', function ($subQuery) use ($date) {
                        $subQuery
                            ->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_PICKUP_CANCELED, RefStatus::CODE_CANCEL_RECEIVE])
                            ->whereDate('created_at', $date);
                    })->with(['statuses',
                        'invoice' => function ($query) {
                            $query->select('id', 'wave_id', 'cash_sum', 'payment_type', 'payment_method', 'invoice_number', 'payer_company_id', 'status_id', 'wait_list_id');
                        },
                        'invoice.wave' => function ($query) {
                            $query->select('id', 'title');
                        },
                        'invoice.cargo' => function ($query) {
                            $query->select('id', 'cod_payment', 'invoice_id', 'weight');
                        },
                        'order' => function ($query) {
                            $query->select('id', 'sender_id', 'number', 'company_id');
                        },
                    ]);
                },
                'deliveries' => function ($query) use ($date) {
                    $query->whereHas('statuses', function ($subQuery) use ($date) {
                        $subQuery
                            ->whereIn('code', [RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED, RefStatus::CODE_COURIER_RETURN_DELIVERY])
                            ->whereDate('created_at', $date);
                    })->with(['statuses',
                        'invoice' => function ($query) {
                            $query->select('id', 'wave_id', 'cash_sum', 'payment_type', 'payment_method', 'order_id', 'invoice_number', 'receiver_id', 'payer_company_id', 'status_id', 'wait_list_id');
                        },
                        'invoice.wave' => function ($query) {
                            $query->select('id', 'title');
                        },
                        'invoice.cargo' => function ($query) {
                            $query->select('id', 'cod_payment', 'invoice_id', 'weight');
                        },
                    ]);
                },
                'closeCourierDays' => function ($query) use ($date) {
                    $query->whereDate('created_at', $date);
                },
                'courierPayments' => function ($query) use ($date) {
                    $query->select(['id', 'courier_id', 'cost', 'type'])
                        ->whereDate('created_at', $date);
                },
            ])
            ->where(function (Builder $query) use ($date) {
                $query
                    ->whereHas('takes', function (Builder $query) use ($date) {
                        $query->whereHas('statuses', function (Builder $subQuery) use ($date) {
                            $subQuery
                                ->whereIn('code', [RefStatus::CODE_CARGO_PICKED_UP, RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_PICKUP_CANCELED, RefStatus::CODE_CANCEL_RECEIVE])
                                ->whereDate('created_at', $date);
                        });
                    })
                    ->orWhereHas('deliveries', function (Builder $query) use ($date) {
                        $query->whereHas('statuses', function ($subQuery) use ($date) {
                            $subQuery
                                ->whereIn('code', [RefStatus::CODE_DELIVERY_IN_PROGRESS, RefStatus::CODE_DELIVERED, RefStatus::CODE_COURIER_RETURN_DELIVERY])
                                ->whereDate('created_at', $date);
                        });
                    });
            })
            ->where('id', $courierId)
            ->get()
            ->firstOrFail();
    }
}
