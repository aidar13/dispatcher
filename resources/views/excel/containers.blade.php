@php
    use App\Module\Planning\Models\Container;
    use App\Module\Delivery\Models\Delivery;
@endphp
    <!doctype html>
<html lang="en">
<body>
<table>
    <thead>
    <tr>
        <th><strong>№</strong></th>
        <th><strong>Контейнер/Номер накладной</strong></th>
        <th><strong>Количество накладных</strong></th>
        <th><strong>Сектор</strong></th>
        <th><strong>Физ. вес</strong></th>
        <th><strong>Объемный вес</strong></th>
        <th><strong>Дата создания</strong></th>
        <th><strong>Дата доставки</strong></th>
        <th><strong>Статус доставки</strong></th>
        <th><strong>Курьер</strong></th>
        <th><strong>Диспетчер</strong></th>
    </tr>
    </thead>
    @php /** @var Container $container */@endphp
    @foreach($containers as $container)
        <tbody>
        <tr>
            <td>{{ $container->id }}</td>
            <td>{{ $container->title }}</td>
            <td>{{ $container->invoices->count() }}</td>
            <td>{{ $container->sector?->name }}</td>
            <td>{{ $container->getWeight() }}</td>
            <td>{{ $container->getVolumeWeight() }}</td>
            <td>{{ $container->created_at->format('Y-m-d H:i:s') }}</td>
            <td></td>
            <td></td>
            <td>{{ $container->courier?->full_name }}</td>
            <td>{{ $container->user?->name }}</td>
        </tr>
        @foreach($container->invoices as $invoice)
            <tr>
                @php
                    /** @var Delivery $delivery */
                    $delivery = $invoice->deliveries->last();
                @endphp
                <td></td>
                <td>{{ $invoice->invoice_number }}</td>
                <td></td>
                <td></td>
                <td>{{ $invoice->cargo?->weight }}</td>
                <td>{{ $invoice->cargo?->volume_weight }}</td>
                <td>{{ $invoice->created_at?->format('Y-m-d H:i:s') }}</td>
                <td>{{ $delivery?->delivered_at }}</td>
                <td>{{ $delivery?->status->title }}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        <tr></tr>
        <tr></tr>
        @endforeach
        </tbody>
</table>
</body>
</html>
