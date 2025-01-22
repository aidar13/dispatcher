<?php

declare(strict_types=1);

namespace App\Module\Delivery\Queries;

use App\Helpers\DateHelper;
use App\Module\Delivery\Contracts\Queries\RouteSheetQuery as RouteSheetQueryContract;
use App\Module\Delivery\DTO\RouteSheetIndexDTO;
use App\Module\Delivery\Models\RouteSheet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class RouteSheetQuery implements RouteSheetQueryContract
{
    public function getById(int $id): RouteSheet
    {
        return RouteSheet::findOrFail($id);
    }

    public function getByRouteSheetNumber(string $number): ?RouteSheet
    {
        return RouteSheet::where('number', $number)->first();
    }

    public function getByInvoiceId(int $invoiceId): RouteSheet
    {
        /** @var RouteSheet */
        return RouteSheet::query()
            ->whereHas(
                'routeSheetInvoices',
                fn($q) => $q->where('invoice_id', $invoiceId)->latest()
            )->latest()->first();
    }

    public function getAllPaginated(RouteSheetIndexDTO $DTO): LengthAwarePaginator
    {
        return RouteSheet::query()
            ->select(['number', 'id', 'status_id', 'date', 'courier_id', 'city_id', 'created_at'])
            ->with([
                'courier:full_name,iin,id,phone_number',
                'city:id,name',
                'routeSheetInvoices:id,route_sheet_id,invoice_id',
                'routeSheetInvoices.invoice:id,wave_id,receiver_id',
                'routeSheetInvoices.invoice.wave:id,title',
                'routeSheetInvoices.invoice.receiver:id,sector_id',
                'routeSheetInvoices.invoice.receiver.sector:id,name',
                'routeSheetInvoices.invoice.cargo:invoice_id,places,weight,volume_weight',
            ])
            ->when($DTO->fromDate, fn(Builder $query) => $query->whereDate('created_at', '>=', $DTO->fromDate))
            ->when($DTO->toDate, fn(Builder $query) => $query->whereDate('created_at', '<=', $DTO->toDate))
            ->when($DTO->courierId, fn(Builder $query) => $query->where('courier_id', $DTO->courierId))
            ->when($DTO->invoiceNumber, function (Builder $query) use ($DTO) {
                $query->whereHas('routeSheetInvoices.invoice', function ($subquery) use ($DTO) {
                    $subquery->where('invoice_number', $DTO->invoiceNumber);
                });
            })
            ->when($DTO->number, fn(Builder $query) => $query->where('number', 'like', "%$DTO->number%"))
            ->when($DTO->cityId, fn(Builder $query) => $query->where('city_id', $DTO->cityId))
            ->when($DTO->sectorId, function (Builder $query) use ($DTO) {
                $query->whereHas('routeSheetInvoices.invoice.receiver.sector', function ($subquery) use ($DTO) {
                    $subquery->where('id', $DTO->sectorId);
                });
            })
            ->when($DTO->waveId, function (Builder $query) use ($DTO) {
                $query->whereHas('routeSheetInvoices.invoice.wave', function ($subquery) use ($DTO) {
                    $subquery->where('id', $DTO->waveId);
                });
            })
            ->when($DTO->dispatcherSectorId, fn(Builder $query) => $query->where('dispatcher_sector_id', $DTO->dispatcherSectorId))
            ->orderByDesc('id')
            ->paginate($DTO->limit, ['*'], 'page', $DTO->page);
    }

    public function getWithInfosById(int $id): RouteSheet
    {
        /** @var RouteSheet $routeSheet */
        $routeSheet = RouteSheet::query()
            ->where('id', $id)
            ->select(['number', 'id', 'status_id', 'date', 'courier_id', 'city_id', 'created_at'])
            ->firstOrFail();

        $createdAt = $routeSheet->created_at;
        $courierId = $routeSheet->courier_id;

        $routeSheet->load([
            'courier:full_name,iin,id,phone_number',
            'city:id,name',
            'routeSheetInvoices:id,route_sheet_id,invoice_id,created_at',
            'routeSheetInvoices.invoice:id,wave_id,receiver_id,invoice_number,order_id,dop_invoice_number,cash_sum',
            'routeSheetInvoices.invoice.statuses:id,invoice_id,code,created_at',
            'routeSheetInvoices.invoice.wave:id,title',
            'routeSheetInvoices.invoice.receiver:id,sector_id,city_id,full_address,comment',
            'routeSheetInvoices.invoice.receiver.sector:id,name',
            'routeSheetInvoices.invoice.cargo:invoice_id,places,weight,volume_weight,cod_payment',
            'routeSheetInvoices.invoice.order:id,company_id,number',
            'routeSheetInvoices.invoice.order.company:id,short_name',
            'routeSheetInvoices.invoice.order.lastWaitListMessage:id,number,comment',
            'routeSheetInvoices.invoice.deliveries' => function ($query) use ($createdAt, $courierId) {
                $query->whereDate('created_at', DateHelper::getDate($createdAt))
                    ->where('courier_id', $courierId)
                    ->select('id', 'invoice_id', 'delivery_receiver_name', 'delivered_at', 'status_id', 'wait_list_status_id', 'courier_id', 'created_at')
                    ->latest();
            },
            'routeSheetInvoices.invoice.deliveries.status:id,title',
            'routeSheetInvoices.invoice.deliveries.refStatus:id,name',
        ]);

        return $routeSheet;
    }
}
