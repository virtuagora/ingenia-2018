<?php
$container = $app->getContainer();

$container['db'] = function ($c) {
    $settings = $c->get('settings')['eloquent'];
    return new App\Service\EloquentService($settings);
};
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['monolog'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(
        $settings['path'],
        Monolog\Logger::DEBUG)
    );
    return $logger;
};
$container['mailer'] = function ($c) {
    $settings = $c->get('settings')['swiftmailer'];
    $mailer = new App\Service\SwiftMailerService(
        $settings['transport'],
        $settings['options']
    );
    return $mailer;
};
$container['view'] = function ($c) {
    $settings = $c->get('settings')['twig'];
    $view = new Slim\Views\Twig($settings['path'], $settings['options']);
    $view->addExtension(new App\Util\TwigExtension(
        $c['router'],
        $c['request']
        //$c['request']->getUri()
    ));
    $view->addExtension(new Twig_Extension_Debug());
    return $view;
};
$container['store'] = function ($c) {
    return new SlimSession\Helper;
};
$container['validation'] = function ($c) {
    return new Augusthur\JsonRespector\ValidatorService();
};

$container['session'] = function ($c) {
    return new App\Service\PHPSessionService($c['store']);
};
$container['identity'] = function ($c) {
    return new App\Service\IdentityService([
        'local' => new App\Util\IdentityProvider\LocalIdentityProvider($c['db'], $c['validation']),
        'facebook' => new App\Util\IdentityProvider\FacebookIdentityProvider($c['db'], $c['facebook']),
    ], $c['db']);
};
$container['representation'] = function ($c) {
    return new App\Service\RepresentationService([
        'html' => new App\Representation\HTMLRepresentation($c['view']),
        'json' => new App\Representation\JSONRepresentation(),
    ]);
};
$container['facebook'] = function ($c) {
    $settings = $c->get('settings')['facebook'];
    return new \Facebook\Facebook($settings);
};

/*$container['App\ExampleController'] = function ($c) {
    return new App\Controller\ExampleController($c);
};*/
$container['sessionAction'] = function ($c) {
    return new App\Action\SessionAction($c['session'], $c['identity'], $c['view']);
};
$container['userAction'] = function ($c) {
    $resource = new App\Resource\UserResource($c);
    return new App\Action\UserAction($resource, $c['representation']);
};

$container['errorHandler'] = function ($c) {
    return new App\Service\ErrorHandlerService(
        [
            '\Respect\Validation\Exceptions\NestedValidationException' => function($r, $e) {
                return $r->withStatus(400)->withJSON([
                    'mensaje' => 'Datos ingresados inválidos',
                    'errores' => $e->getMessages(),
                ]);
            },
            '\Illuminate\Database\Eloquent\ModelNotFoundException' => function($r, $e) {
                return $r->withStatus(404)->withJSON([
                    'mensaje' => 'Recurso no encontrado',
                ]);
            },
            '\App\Util\Exception\AppException' => function($r, $e) {
                return $r->withStatus($e->getCode())->withJSON([
                    'mensaje' => $e->getMessage(),
                ]);
            },
            '\App\Util\Exception\SystemException' => function($r, $e) {
                return $r->withStatus(500)->withJSON([
                    'mensaje' => 'Error crítico del sistema',
                ]);
            },
        ],
        $c->get('logger')
    );
};

// $container['errorHandler'] = function ($c) {
//     return function ($req, $res, $e) use ($c) {
//         $error = [
//             'message' => 'Internal error'
//         ];
//         $code = 500;
//         $c->get('logger')->info($e->getMessage());

//         $c->get('logger')->info($req);

//         if ($e instanceof App\Util\Exception\AppException) {
//             $code = $e->getCode();
//             $error['message'] = $e->getMessage();
//         } elseif ($e instanceof App\Util\Exception\SystemException) {
//             $error['message'] = 'Error en el sistema. Por favor contacte un administrador';
//         // } elseif ($e instanceof Facebook\Exceptions\FacebookResponseException) {
//         //     $error['message'] = 'En este momento no podemos comunicarnos con Facebook';
//         // } elseif ($e instanceof Facebook\Exceptions\FacebookSDKException) {
//         //     $error['message'] = 'Facebook no nos permite acceder a tu cuenta en este momento';
//         } elseif ($e instanceof Illuminate\Database\Eloquent\ModelNotFoundException) {
//             $code = 404;
//             $error['message'] = 'Recurso no encontrado.';
//         }
//         $options = ['template' => 'error.html.twig'];

//         return $res->withJSON($error)->withStatus($code);

//         /*return $c->get('representation')->convert(
//             $error, $req, $res->withStatus($code), $options
//         );*/
//     };
// };
