<!doctype html>
<html lang="en">
<body>
<table>
    <thead>
    <tr>
        <th style="text-align: center">Статус</th>
        <th style="text-align: center">% от заказов</th>
        <th style="text-align: center">Количество</th>
        <th><strong>Отчет по заборам</strong></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Не назначено</td>
        <td>{{ $percentOfNotAssigned }}%</td>
        <td>{{ $numberOfNotAssigned }}</td>
    </tr>
    <tr>
        <td>Назначено</td>
        <td>{{ $percentOfAssigned }}%</td>
        <td>{{ $numberOfAssigned }}</td>
    </tr>
    <tr>
        <td>ИТОГО:</td>
        <td>100%</td>
        <td>{{ $numberOfNotAssigned + $numberOfAssigned }}</td>
    </tr>
    <tr>
        <td>Назначено</td>
        <td>{{ $percentOfAssigned2 }}%</td>
        <td>{{ $numberOfAssigned }}</td>
    </tr>
    <tr>
        <td>Назначено и забрано</td>
        <td>{{ $percentOfTaken }}%</td>
        <td>{{ $numberOfTaken }}</td>
    </tr>
    <tr>
        <td>Назначено и не забрано</td>
        <td>{{ $percentOfAssignedAndNotTaken }}%</td>
        <td>{{ $numberOfAssignedAndNotTaken }}</td>
    </tr>
    <tr>
        <td>ИТОГО:</td>
        <td>100%</td>
        <td>{{ $numberOfAssigned + $numberOfTaken + $numberOfAssignedAndNotTaken }}</td>
    </tr>
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th><strong>Номер заказа</strong></th>
        <th><strong>Не назначено</strong></th>
        <th><strong>Назначено</strong></th>
        <th><strong>Назначено и не забрано</strong></th>
        <th><strong>Назначено и Забрано</strong></th>
        <th><strong>Текущий Статус</strong></th>
        <th><strong>Поступило на склад</strong></th>
        <th><strong>Город</strong></th>
        <th><strong>Сектор</strong></th>
        <th><strong>Отправитель</strong></th>
        <th><strong>Клиент</strong></th>
        <th><strong>Курьер</strong></th>
        <th><strong>Самопривоз</strong></th>
        <th><strong>Адрес забора</strong></th>
        <th><strong>КОЛИЧЕСТВО НАКЛАДНЫХ В ЗАКАЗЕ</strong></th>
        <th><strong>КОЛИЧЕСТВО ЗАБРАННЫХ НАКЛАДНЫХ</strong></th>
        <th><strong>Количество мест</strong></th>
        <th><strong>Период</strong></th>
        <th><strong>Физ. вес</strong></th>
        <th><strong>Объемный вес</strong></th>
        <th><strong>Дата забора(плановый)</strong></th>
        <th><strong>Дата забора (фактический)</strong></th>
        <th><strong>Вид перевозки</strong></th>
        <th><strong>Статус ЛО</strong></th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $item)
        <tr>
            <td>{{ Arr::get($item, 'orderNumber') }}</td>
            <td>{{ Arr::get($item, 'isNotAssigned') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'isAssigned') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'isAssignedAndNotTaken') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'isTaken') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'status') }}</td>
            <td>{{ Arr::get($item, 'isDeliveredToWarehouse') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'city') }}</td>
            <td>{{ Arr::get($item, 'sector') }}</td>
            <td>{{ Arr::get($item, 'sender') }}</td>
            <td>{{ Arr::get($item, 'receiver') }}</td>
            <td>{{ Arr::get($item, 'courier') }}</td>
            <td>{{ Arr::get($item, 'isPickUp') ? 'Да' : 'Нет' }}</td>
            <td>{{ Arr::get($item, 'orderTakeAddress') }}</td>
            <td>{{ Arr::get($item, 'numberOfInvoices') }}</td>
            <td>{{ Arr::get($item, 'numberOfTakenInvoices') }}</td>
            <td>{{ Arr::get($item, 'numberOfPlaces') }}</td>
            <td>{{ Arr::get($item, 'period') }}</td>
            <td>{{ Arr::get($item, 'totalWeight') }}</td>
            <td>{{ Arr::get($item, 'totalVolumeWeight') }}</td>
            <td>{{ Arr::get($item, 'planningTakeDate') }}</td>
            <td>{{ Arr::get($item, 'actualTakeDate') }}</td>
            <td>{{ Arr::get($item, 'shipmentType') }}</td>
            <td>{{ Arr::get($item, 'waitListStatus') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
