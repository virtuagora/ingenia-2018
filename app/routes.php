<?php

// misc

// pantalla con botón login con facebook
$app->get('/fb-login-js', function ($request, $response, $args) {
    return $this->view->render($response, 'test/fb-login-js.twig', [
    ]);
});

// hacer un post a esto con un campo "access" que tiene el access token
// si el usuario existe hay login exitoso
// si el usuario no existe en BD se muestra pantalla de confirmar registro
$app->post('/fb-callback', function ($request, $response, $args) {
    $result = $this->identity->signIn('facebook', $request->getParsedBody());
    if ($result['status'] == 'success') {
        $user = $result['user'];
        $session = $this->session->signIn($user->subject->toDummy());
        return $response->withRedirect('/idle');
    } elseif ($result['status'] == 'pending-user') {
        return $this->view->render($response, 'test/fb-register.twig', [
            'token' => $result['token'],
        ]);
    } else {
        return $this->view->render($response, 'test/fb-login-link.twig', [
            'link' => $result['status'],
        ]);
    }
})->setName('fbCallbackGet');

// pantalla de confirmar registro, si se da ok el usuario entra identificado
$app->post('/fb-register', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $user = $this->identity->registerUser([], $data['token']);
    $session = $this->session->signIn($user->subject->toDummy());
    return $response->withRedirect('/idle');
})->setName('fbRegister');

// pantalla que muestra si el usuario está identificado o no
$app->get('/idle', function ($request, $response, $args) {
    $sub = $request->getAttribute('subject');
    return $this->view->render($response, 'test/simple.twig', [
        'text' => $sub->getType(),
    ]);
});

$app->get('/logout', function ($request, $response, $args) {
    $this->session->signOut();
    return $this->view->render($response, 'test/simple.twig', [
        'text' => 'holis',
    ]);
});


///

$app->get('/', function ($request, $response, $args) {
    $this->logger->info('Hello!');
    
    return $this->view->render($response, 'index.twig', [
    'name' => 'hello!',
    'sub' => $request->getAttribute('subject'),
    ]);
})->setName('showHome');

$app->get('/install[/{extra}]', function ($request, $response, $args) {
    if (!isset($args['extra'])) {
        $installer = new \App\Util\Installer($this->db);
        $installer->down();
        $installer->up();
        $installer->populate();
        $loader = new \App\Util\LocalitiesLoader($this->db);
        $loader->up();
    }
    $actions = new \App\Util\ActionsLoader($this->db);
    $actions->up();
    return $response->withJSON(['message' => 'instalación exitosa']);
});

$app->get('/test', function ($req, $res, $arg) {
    return $res->withJSON([
    'sub' => $this->session->authenticate($req)->toArray()
    ]);
    //return $res->withJSON($this->session->get('user'));
});

// views

$app->get('/login', function ($request, $response, $args) {
    return $this->view->render($response, 'base/login.twig', [
        'facebookKey' => $this->get('settings')['facebook']['app_id'],
        'googleKey' => $this->get('settings')['recaptcha']['public_key'],
    ]);
})->setName('showLogin');

$app->get('/sign-up', function ($request, $response, $args) {
    return $this->view->render($response, 'base/signUp.twig');
})->setName('showSignUp');

$app->get('/complete-sign-up', function ($request, $response, $args) {
    return $this->view->render($response, 'base/completar-registro.twig', [
    'activation_key' => $request->getQueryParam('token'),
    ]);
})->setName('showCompleteSignUp');

// api

$app->get('/category', 'miscAction:getCategories');
$app->get('/region', 'miscAction:getRegions');
$app->get('/region/{reg}/department', 'miscAction:getDepartments');
$app->get('/department/{dep}/locality', 'miscAction:getLocalities');

$app->get('/user/{usr}', 'userAction:getOne');
$app->post('/user/{usr}/profile', 'userAction:postProfile')->setName('runUpdUserPro');
$app->post('/user/{usr}/dni', 'userAction:postDni')->setName('runUpdUserDni');

$app->post('/group', 'groupAction:post')->setName('runCreGro');
$app->post('/group/{gro}/invitation', 'groupAction:postInvitation')->setName('runCreGroInv');

$app->post('/project', 'projectAction:post')->setName('runCrePro');

$app->post('/login', 'sessionAction:formLocalLogin')->setName('runLogin');

$app->post('/pending-user', 'userAction:postPendingUser')->setName('runNewPendingUser');
$app->post('/user', 'userAction:post')->setName('runNewUser');

// guille

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

$app->group('/panel', function () {
    $this->get('', function ($req, $res, $arg) {
        return $this->view->render($res, 'ingenia/panel/userPanel.twig', []);
    });
    $this->get('/[{path:.*}]', function ($req, $res, $arg) {
        return $this->view->render($res, 'ingenia/panel/userPanel.twig', [
        ]);
    });
    // $this->get('/perfil', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/perfil/editar', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/invitaciones', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/integrante/datos', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/integrante/dni', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/start', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/proyecto', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/proyecto/editar', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/equipo', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/equipo/editar', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/equipo/invitaciones', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/proyecto/imagen', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
    // $this->get('/ingenia/publicar', function ($req, $res, $arg) {
    //     return $this->view->render($res, 'panel/userPanel.twig', []);
    // });
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