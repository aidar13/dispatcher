<?php

use Ludovicose\TransactionOutbox\Brokers\RabbitMQBroker;
use Ludovicose\TransactionOutbox\Serializers\JsonEventPublishSerializer;
use Ludovicose\TransactionOutbox\Repositories\MemoryEventRepository;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

return [
    'table_name'               => 'events',
    'delete_last_event_in_day' => 10,
    'event_repository'         => MemoryEventRepository::class,
    'event_publish_serialize'  => JsonEventPublishSerializer::class,

    'event_normalizers' => [
        DateTimeNormalizer::class,
        ObjectNormalizer::class,
        ArrayDenormalizer::class,
    ],

    'broker' => RabbitMQBroker::class,

    'subscribe_channels' => [
        'cabinet.company.created',
        'cabinet.company.updated',
        'location.city.created',
        'location.city.updated',
        'location.region.created',
        'cabinet.country.created',
        'cabinet.courier-app.courier.created',
        'cabinet.courier.created',
        'cabinet.courier.updated',
        'cabinet.car.created',
        'cabinet.car.updated',
        'cabinet.courier-app.take-info.created',
        'cabinet.courier-app.take-info.updated',
        'cabinet.courier-app.delivery-info.created',
        'cabinet.courier-app.delivery-info.updated',
        'cabinet.courier-app.courier-stop.created',
        'cabinet.order.created',
        'cabinet.order.updated',
        'cabinet.invoice.created',
        'cabinet.invoice.updated',
        'cabinet.receiver.created',
        'cabinet.receiver.updated',
        'cabinet.sender.created',
        'cabinet.sender.updated',
        'cabinet.sla.created',
        'cabinet.sla.updated',
        'cabinet.order-status.created',
        'cabinet.file.created',
        'cabinet.courier-app.delivery-info.wait-list.set',
        'cabinet.courier-app.take-info.wait-list.set',
        'cabinet.car.occupancy.created',
        'cabinet.courier.payment.created',
        'cabinet.additional-service-value.created',
        'cabinet.additional-service-value.updated',
        'cabinet.additional-service-value.deleted',
        'cabinet.wait-list-status.created',
    ],

    'enable_request_log' => false,
    'serviceName'        => env("SERVICE_NAME", 'serviceName'),

    'rabbitmq' => [
        'default_type' => 'fanout',

        'hosts' => [
            [
                'host'     => env('RABBITMQ_HOST', 'rabbitmq'),
                'port'     => env('RABBITMQ_PORT', 5672),
                'user'     => env('RABBITMQ_USER', 'guest'),
                'password' => env('RABBITMQ_PASSWORD', 'guest'),
                'vhost'    => env('RABBITMQ_VHOST', '/'),
            ],
        ],

        'options' => [
            'ssl_options' => [
                'cafile'      => env('RABBITMQ_SSL_CAFILE', null),
                'local_cert'  => env('RABBITMQ_SSL_LOCALCERT', null),
                'local_key'   => env('RABBITMQ_SSL_LOCALKEY', null),
                'verify_peer' => env('RABBITMQ_SSL_VERIFY_PEER', true),
                'passphrase'  => env('RABBITMQ_SSL_PASSPHRASE', null),
            ],

            'message-ttl' => 0,
            'heartbeat'   => 60,

            'queue' => [
                'declare' => false,
                'bind'    => false,
            ],
        ],
    ]
];
