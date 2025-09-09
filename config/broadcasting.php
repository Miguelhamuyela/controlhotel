<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | Esta opção controla o broadcaster padrão que será usado pelo framework
    | quando um evento precisar ser transmitido. Você pode definir isso para
    | qualquer uma das conexões definidas no array "connections" abaixo.
    |
    | Suportados: "pusher", "ably", "redis", "log", "null"
    |
    */

    'default' => env('BROADCAST_DRIVER', 'pusher'),

    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Aqui você pode definir todas as conexões de broadcast que serão utilizadas
    | para transmitir eventos para outros sistemas ou via websockets. Amostras de
    | cada tipo disponível de conexão são fornecidas dentro deste array.
    |
    */

    'connections' => [

        'pusher' => [
            'driver'  => 'pusher',
            'key'     => env('PUSHER_APP_KEY'),
            'secret'  => env('PUSHER_APP_SECRET'),
            'app_id'  => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS'  => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key'    => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
