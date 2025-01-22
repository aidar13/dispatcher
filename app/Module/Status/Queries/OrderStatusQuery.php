<?php

declare(strict_types=1);

namespace App\Module\Status\Queries;

use App\Module\Status\Contracts\Queries\OrderStatusQuery as OrderStatusQueryContract;
use App\Module\Status\Models\OrderStatus;
use App\Module\Status\Models\RefStatus;

final class OrderStatusQuery implements OrderStatusQueryContract
{
    public function getById(int $id): OrderStatus
    {
        return OrderStatus::findOrFail($id);
    }

    public function getLastByInvoiceId(int $invoiceId): ?OrderStatus
    {
        /** @var OrderStatus|null */
        return OrderStatus::query()
            ->where('invoice_id', $invoiceId)
            ->latest('id')
            ->first();
    }

    public function getStatusForWaveByInvoiceId(int $invoiceId): ?OrderStatus
    {
        return OrderStatus::query()
            ->where('invoice_id', $invoiceId)
            ->where('code', RefStatus::CODE_CARGO_ARRIVED_CITY)
            ->orderByDesc('id')
            ->firstOr(function () use ($invoiceId) {
                return OrderStatus::query()
                    ->where('invoice_id', $invoiceId)
                    ->where('code', RefStatus::CODE_APPROXIMATE_DELIVERY_TO_CITY)
                    ->orderByDesc('id')
                    ->firstOr(function () use ($invoiceId) {
                        return OrderStatus::query()
                            ->where('invoice_id', $invoiceId)
                            ->where('code', RefStatus::CODE_CARGO_AWAIT_SHIPMENT)
                            ->orderByDesc('id')
                            ->first();
                    });
            });
    }

    public function getLastTakeStatusInvoiceIdAndDate(int $invoiceId, string $date): ?OrderStatus
    {
        return OrderStatus::query()
            ->where('invoice_id', $invoiceId)
            ->whereIn('code', [RefStatus::CODE_CARGO_HANDLING, RefStatus::CODE_CARGO_AWAIT_SHIPMENT])
            ->whereDate('created_at', $date)
            ->orderByDesc('id')
            ->firstOr(function () use ($invoiceId, $date) {
                return OrderStatus::query()
                    ->where('invoice_id', $invoiceId)
                    ->where('code', RefStatus::CODE_CARGO_PICKED_UP)
                    ->whereDate('created_at', $date)
                    ->orderByDesc('id')
                    ->firstOr(function () use ($invoiceId, $date) {
                        return OrderStatus::query()
                            ->where('invoice_id', $invoiceId)
                            ->where('code', RefStatus::CODE_ASSIGNED_TO_COURIER)
                            ->whereDate('created_at', $date)
                            ->orderByDesc('id')
                            ->first();
                    });
            });
    }
}
