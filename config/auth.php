<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Essas configurações definem o "guard" de autenticação e as opções de reset
    | de senha padrão para sua aplicação. Como você está utilizando a model Admin,
    | alteramos para usar o guard e provider 'admins'.
    |
    */

    'defaults' => [
        'guard' => 'admin',
        'passwords' => 'admins',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Aqui definimos os "guards" de autenticação para a aplicação. Estamos usando
    | o driver 'session' para o guard 'admin' e também para a API com o Sanctum.
    |
    */

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Aqui definimos como os administradores são recuperados do banco de dados.
    | Usamos o driver Eloquent com a model App\Models\Admin.
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
            ],
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employeee::class,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | As configurações para reset de senha. Aqui, definimos para o provider 'admins'
    | utilizando a tabela 'password_resets'. Você pode ajustar os tempos de expiração
    | e throttling conforme necessário.
    |
    */

    'passwords' => [
    'admins' => [
        'provider' => 'admins',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
    ],
    'employees' => [
        'provider' => 'employees',
        'table' => 'password_resets',
        'expire' => 60,
        'throttle' => 60,
    ],
],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Tempo (em segundos) antes de uma confirmação de senha expirar.
    | Por padrão, o timeout é de 10800 segundos (3 horas).
    |
    */

    'password_timeout' => 10800,

];
