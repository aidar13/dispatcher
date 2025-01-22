<?php

return [
    'container' => [
        'assembled' => [
            'subject' => 'Контейнер :containerName с ID :containerId был частично собран',
            'content' => 'В контейнере :containerName с ID :containerId есть частично собранные накландые: :invoiceNumbers'
        ]
    ],

    'courier_app' => [
        'shortcoming' => [
            'created' => [
                'subject' => 'Акт об обнаружении недостатков',
                'content' => 'По заказу :orderNumber был сформирован акт об обнаружении недостатков/утраты/излишки/порчи товара.<a href=":uri/order/:orderId">Открыть заказ</a>'
            ]
        ]
    ],
];
