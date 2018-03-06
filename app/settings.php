<?php

return [
    'settings' => [
        'mode' => 'dev',
        'determineRouteBeforeAppMiddleware' => true,
        'displayErrorDetails' => true,
        'timezone' => 'America/Argentina/Buenos_Aires',
        'twig' => [
            'path' => __DIR__.'/templates',
            'options' => [
                'cache' => __DIR__.'/../var/cache',
                'debug' => true,
            ],
        ],
        'swiftmailer' => [
            'transport' => 'null',
            'options' => [],
        ],
        'monolog' => [
            'name' => 'monolog',
            'path' => __DIR__.'/../var/logs/app.log',
        ],
        'eloquent' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'virtuagora_next',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => '',
        ],
        'store' => [
            'name' => 'virtuagora',
            'autorefresh' => true,
            'lifetime' => '1 hour',
        ],
    ],
];
