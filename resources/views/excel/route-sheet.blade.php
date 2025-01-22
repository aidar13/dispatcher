@php
use App\Module\Delivery\Models\RouteSheet;
use App\Module\Order\Models\Invoice;
use App\Helpers\DateHelper;
use App\Module\Status\Models\RefStatus;

    /**
    * @var RouteSheet $routeSheet
    */
@endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th><strong>Номер накладной</strong></th>
        <th><strong>Клиент</strong></th>
        <th><strong>Статус</strong></th>
        <th><strong>Статус ЛО</strong></th>
        <th><strong>Дата доставки факт</strong></th>
        <th><strong>Возврат выдачи</strong></th>
        <th><strong>Город, сектор</strong></th>
        <th><strong>Волна</strong></th>
        <th><strong>Адрес</strong></th>
        <th><strong>Комментарий</strong></th>
        <th><strong>Груз принял</strong></th>
        <th><strong>Кол-во мест</strong></th>
        <th><strong>Физ вес</strong></th>
        <th><strong>Обьемный вес</strong></th>
        <th><strong>Номер накладной заказчика</strong></th>
        <th><strong>Сумма наложенного платежа</strong></th>
        <th><strong>Сумма наличных</strong></th>
    </tr>
    <?php /** @var Invoice $invoice */ ?>
    @foreach($routeSheet->routeSheetInvoices as $invoice)
        <tr>
            <td>{{ $invoice?->invoice->invoice_number }}</td>
            <td>{{ $invoice?->invoice->order?->company?->short_name }}</td>
            <td>{{ $invoice?->invoice->deliveries?->last()?->status?->title }}</td>
            <td>{{ $invoice?->invoice->deliveries?->last()?->refStatus?->title }}</td>
            <td>{{ $invoice?->invoice->deliveries?->last()?->delivered_at ?: null }}</td>
            <td>{{ DateHelper::getDateWithTime($invoice?->invoice->statuses->where('code', RefStatus::CODE_COURIER_RETURN_DELIVERY)?->last()?->created_at) }}</td>
            <td>{{ $invoice?->invoice->receiver?->city?->name . ' / ' . $invoice?->invoice->receiver?->sector?->name }}</td>
            <td>{{ $invoice?->invoice->wave?->title }}</td>
            <td>{{ $invoice?->invoice->receiver?->full_address }}</td>
            <td>{{ $invoice?->invoice->lastWaitListMessage?->comment  }}</td>
            <td>{{ $invoice?->invoice->deliveries?->last()?->delivery_receiver_name }}</td>
            <td>{{ $invoice?->invoice->cargo?->places }}</td>
            <td>{{ $invoice?->invoice->cargo?->weight }}</td>
            <td>{{ $invoice?->invoice->cargo?->volume_weight }}</td>
            <td>{{ $invoice?->invoice->dop_invoice_number }}</td>
            <td>{{ $invoice?->invoice->cargo?->cod_payment }}</td>
            <td>{{ $invoice?->invoice->cash_sum }}</td>
        </tr>
    @endforeach
    </thead>
</table>
</body>
</html>
