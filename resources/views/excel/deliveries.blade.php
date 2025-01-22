<!doctype html>
<html lang="en">
<body>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Статус</th>
        <th style="text-align: center">% от заказов</th>
        <th style="text-align: center">Количество</th>
        <th><strong>Отчет по доставке</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Выдан, не доставлен</td>
        <td>{{round(($numberOfInProgressStatuses / $totalNumberOfStatuses) * 100, 2)}}%</td>
        <td>{{$numberOfInProgressStatuses}}</td>
    </tr>
    <tr>
        <td>Выдан , доставлен</td>
        <td>{{round(($numberOfDeliveredStatuses / $totalNumberOfStatuses) * 100, 2)}}%</td>
        <td>{{$numberOfDeliveredStatuses}}</td>
    </tr>
    <tr>
        <td>Выдан, не доставлен, оформлен возврат</td>
        <td>{{round(($numberOfCancelledStatuses / $totalNumberOfStatuses) * 100, 2)}}%</td>
        <td>{{$numberOfCancelledStatuses}}</td>
    </tr>
    <tr>
        <td>ИТОГО:</td>
        <td>100%</td>
        <td>{{$totalNumberOfStatuses}}</td>
    </tr>
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th><strong>Город Назначения</strong></th>
        <th><strong>Сектор</strong></th>
        <th><strong>Номер накладной</strong></th>
        <th><strong>Выдан, не доставлен</strong></th>
        <th><strong>Выдан, доставлен</strong></th>
        <th><strong>Выдан, не доставлен, оформлен возврат</strong></th>
        <th><strong>ГО</strong></th>
        <th><strong>ДК</strong></th>
        <th><strong>ДР</strong></th>
        <th><strong>ВЗ</strong></th>
        <th><strong>ОЗ</strong></th>
        <th><strong>Номер возвратной</strong></th>
        <th><strong>Текущий Статус</strong></th>
        <th><strong>Статус ЛО Последний статус из листа ожидания</strong></th>
        <th><strong>Контрагент</strong></th>
        <th><strong>Количество мест</strong></th>
        <th><strong>Физ. вес</strong></th>
        <th><strong>Объемный вес</strong></th>
        <th><strong>Адрес доставки</strong></th>
        <th><strong>Самовывоз</strong></th>
        <th><strong>Курьер</strong></th>
        <th><strong>Клиент</strong></th>
        <th><strong>Вид перевозки</strong></th>
        <th><strong>Дата создания заказа</strong></th>
        <th><strong>Дата забора фактический</strong></th>
        <th><strong>Дата время дост в рег (дата получения статуса прибыл в город назначения)</strong></th>
        <th><strong>Дата Доставки факт</strong></th>
        <th><strong>Дата возврата</strong></th>
        <th><strong>Причина возврата</strong></th>
        <th><strong>ЛО (подтвержденные)</strong></th>
        <th><strong>ЛО (не подтвержденные)</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($deliveries as $delivery)
        <tr>
            <td>{{ Arr::get($delivery, 'cityName') }}</td>
            <td>{{ Arr::get($delivery, 'sector') }}</td>
            <td>{{ Arr::get($delivery, 'invoiceNumber') }}</td>
            <td>{{ Arr::get($delivery, 'isInProgress') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'isDelivered') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'isCancelled') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'hasCodeCargoAwaitShipment') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'hasCodeDeliveryInProgress') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'hasCodeCargoArrivedCity') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'hasCodeCargoReturned') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'hasCodeOrderCancelled') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'returnedInvoiceNumber') }}</td>
            <td>{{ Arr::get($delivery, 'status') }}</td>
            <td>{{ Arr::get($delivery, 'waitListStatus') }}</td>
            <td>{{ Arr::get($delivery, 'companyName') }}</td>
            <td>{{ Arr::get($delivery, 'places') }}</td>
            <td>{{ Arr::get($delivery, 'weight') }}</td>
            <td>{{ Arr::get($delivery, 'volumeWeight') }}</td>
            <td>{{ Arr::get($delivery, 'receiverFullAddress') }}</td>
            <td>{{ Arr::get($delivery, 'isPickUp') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($delivery, 'courierFullName') }}</td>
            <td>{{ Arr::get($delivery, 'receiverFullName') }}</td>
            <td>{{ Arr::get($delivery, 'shipmentType') }}</td>
            <td>{{ Arr::get($delivery, 'invoiceCreatedAt') }}</td>
            <td>{{ Arr::get($delivery, 'deliveryCreatedAt') }}</td>
            <td>{{ Arr::get($delivery, 'cargoArrivedCityDate') }}</td>
            <td>{{ Arr::get($delivery, 'deliveredAt') }}</td>
            <td>{{ Arr::get($delivery, 'cancelledAt') }}</td>
            <td>{{ Arr::get($delivery, 'cancellationReason') }}</td>
            <td>
                @foreach(Arr::get($delivery, 'waitListConfirmed') as $item)
                    {{ $item?->refStatus?->name }} ( {{ $item->created_at?->toDateTimeString() }} )
                @if(!$loop->last)
                    <br>
                @endif
                @endforeach
            </td>
            <td>
                @foreach(Arr::get($delivery, 'waitListNotConfirmed') as $item)
                    {{ $item?->refStatus?->name }} ({{ $item->created_at?->toDateTimeString() }})
                    @if(!$loop->last)
                        <br>
                    @endif
                @endforeach
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
