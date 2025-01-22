<?php

use App\Module\Routing\Models\Routing;

return [
    'url'       => env('YANDEX_ROUTING_URL', 'https://courier.yandex.ru'),
    'api_key'   => env('YANDEX_ROUTING_API_KEY', ''),
    'token'     => env('YANDEX_ROUTING_TOKEN', ''),
    'companyId' => env('YANDEX_ROUTING_COMPANY_ID', Routing::YANDEX_COMPANY_ID),
];
