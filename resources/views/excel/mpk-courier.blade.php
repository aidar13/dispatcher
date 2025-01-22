<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<p>{{ $subject }}</p>
<table>
    <thead>
    <tr>
        <th><strong>ID курьера</strong></th>
        <th><strong>ФИО</strong></th>
        <th><strong>Статус</strong></th>
        <th><strong>Сектор диспетчера</strong></th>
    </tr>
    @foreach($couriers as $courier)
        <tr>
            <td>{{ $courier['id'] }}</td>
            <td>{{ $courier['fullName'] }}</td>
            <td>{{ $courier['statusTitle'] }}</td>
            <td>{{ $courier['dsName'] }}</td>
    @endforeach
    </thead>
</table>
</body>
</html>
