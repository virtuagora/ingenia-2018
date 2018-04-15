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
            'database' => 'ingenia2018',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => '',
        ],
        'filesystem' => [
            'adapter' => 'League\Flysystem\Adapter\Local',
            'args' => [
                __DIR__ . '/../var/files',
            ],
        ],
        'store' => [
            'name' => 'virtuagora',
            'autorefresh' => true,
            'lifetime' => '1 hour',
        ],
        'facebook' => [
            'app_id' => '360875627728979',
            'app_secret' => '69bb431bb9ffcdce6fddd186ed062d76',
            'default_graph_version' => 'v2.12',
        ],
        'recaptcha' => [
            'public_key' => '6LcF-CYTAAAAAOdQ_mLyGnTl6uqw1FGj7yrpfW_F',
            'secret_key' => '6LcF-CYTAAAAACfCi_a60IK5E57PGC0xDp4Ko5ex',
        ],
    ],
];
