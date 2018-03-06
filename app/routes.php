<?php

$app->get('/', function ($request, $response, $args) {
    $this->logger->info('Hello!');
    
    return $this->view->render($response, 'index.twig', [
    'name' => 'hello!',
    'sub' => $request->getAttribute('subject'),
    ]);
})->setName('showHome');

$app->get('/install', function ($req, $res, $arg) {
    $installer = new \App\Util\Installer($this->db);
    $installer->down();
    $installer->up();
    
    return $res->withJSON(['message' => 'instalación exitosa']);
});

$app->get('/tast', function ($request, $response, $arg) {
    $this->logger->info($request->getAttributes());
    return $response->withJSON($request->getAttributes());
});

$app->get('/tust', function ($req, $res, $arg) {
    session_start();
    
    return $res->withJSON([$_SESSION]);
});

$app->get('/test', function ($req, $res, $arg) {
    return $res->withJSON([
    'sub' => $this->session->authenticate($req)->toArray()
    ]);
    //return $res->withJSON($this->session->get('user'));
});

$app->get('/tist', function ($req, $res, $arg) {
    return $res->withJSON([
    'user' => $this->session->get('user'),
    'status' => session_status(),
    'holis' => (array) $this->session,
    ]);
    //return $res->withJSON($this->session->get('user'));
});

$app->get('/login', function ($request, $response, $args) {
    return $this->view->render($response, 'base/login.twig');
})->setName('showLogin');

$app->get('/sign-up', function ($request, $response, $args) {
    return $this->view->render($response, 'base/signUp.twig');
})->setName('showSignUp');

$app->get('/complete-sign-up', function ($request, $response, $args) {
    return $this->view->render($response, 'base/completeSignUp.twig', [
    'activation_key' => $request->getQueryParam('token'),
    ]);
})->setName('showCompleteSignUp');

$app->post('/login', 'sessionAction:formLocalLogin')->setName('runLogin');

$app->post('/pending-user', 'userAction:postEmail')->setName('runRegisterEmail');
$app->post('/user', 'userAction:post')->setName('runNewUser');

//$app->get('/send-mail', 'App\ExampleController:sendMail');

//$app->get('/query-db', 'App\ExampleController:queryDB');

$app->get('/testing', function ($req, $res, $arg) {
    return $this->view->render($res, 'node/vote/showVote.twig', [
    ]);
});

$app->group('/settings', function () {
    $this->get('', function ($req, $res, $arg) {
        return $this->view->render($res, 'base/appSettings.twig', [
        ]);
    });
    $this->get('/[{path:.*}]', function ($req, $res, $arg) {
        return $this->view->render($res, 'base/appSettings.twig', [
        ]);
    });
});

$app->group('/node/create', function () {
    $this->get('', function ($req, $res, $arg) {
        return $this->view->render($res, 'node/create.twig', [
        ]);
    });
    $this->get('/[{path:.*}]', function ($req, $res, $arg) {
        return $this->view->render($res, 'node/create.twig', [
        ]);
    });
});

$app->group('/users', function () {
    $this->get('/{id:[0-9]+}', function ($req, $res, $arg) {
        return $this->view->render($res, 'base/userProfile.twig', [
        ]);
    });
});

$app->group('/account', function () {
    $this->get('', function ($req, $res, $arg) {
        return $this->view->render($res, 'base/accountSettings.twig', [
        ]);
    });
    $this->get('/[{path:.*}]', function ($req, $res, $arg) {
        return $this->view->render($res, 'base/accountSettings.twig', [
        ]);
    });
});

$app->get('/testing2', function ($req, $res, $arg) {
    return $this->view->render($res, 'base/completeSignUp.twig', [
    ]);
});

$app->get('/testing3', function ($req, $res, $arg) {
    return $this->view->render($res, 'test3.twig', [
    ]);
});


$app->post('/testing/comment', 'testingAction:commentNodeAction')->setName('commentNode');