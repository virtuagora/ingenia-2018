<?php

// misc

use App\Util\Exception\UnauthorizedException;

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
        $group = $user->groups->first();
        $session = $this->session->signIn($user->subject->toDummy([
        'user_id' => $user->id,
        'group' => [
        'id' => $group->id,
        'relation' => $group->pivot->relation,
        'name' => $group->name,
        ],
        ]));
        return $response->withRedirect('/');
    } elseif ($result['status'] == 'pending-user') {
        return $this->view->render($response, 'base/facebook-registro.twig', [
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
    $group = $user->groups->first();
    $session = $this->session->signIn($user->subject->toDummy([
    'user_id' => $user->id,
    'group' => [
    'id' => $group->id,
    'relation' => $group->pivot->relation,
    'name' => $group->name,
    ],
    ]));
    return $response->withRedirect('/');
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
    return $response->withRedirect('/');
})->setName('showLogout');


///

$app->get('/', function ($request, $response, $args) {
    // $this->logger->info('Hello!');
    return $this->view->render($response, 'index.twig', [
    // 'name' => 'hello!',
    // 'sub' => $request->getAttribute('subject'),
    'headerActive' => 'showHome'
    ]);
    // if ($request->getAttribute('subject')->getType() != 'Annonymous') {
    //     return $this->view->render($response, 'index.twig', [
    //     // 'name' => 'hello!',
    //     // 'sub' => $request->getAttribute('subject'),
    //     'headerActive' => 'showHome'
    //     ]);
    // }
    // return $this->view->render($response, 'ingenia/index/proyectos.twig', [
    // 'headerActive' => 'showCatalogo'
    // ]);
})->setName('showHome');

$app->get('/reglamento', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/reglamento.twig', [
    'headerActive' => 'showReglamento'
    ]);
})->setName('showReglamento');

$app->get('/acerca', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/acerca.twig', [
    'headerActive' => 'showAcerca'
    ]);
})->setName('showAcerca');

$app->get('/privacidad', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/privacidad.twig', [
    'headerActive' => 'showPrivacidad'
    ]);
})->setName('showPrivacidad');

$app->get('/proyectos', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/proyectos.twig', [
    'headerActive' => 'showCatalogo'
    ]);
})->setName('showCatalogo');

$app->get('/ayuda', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/ayuda.twig', [
    'headerActive' => 'showAyuda'
    ]);
})->setName('showAyuda');

$app->get('/como-participar', function ($request, $response, $args) {
    return $this->view->render($response, 'ingenia/index/comoParticipar.twig', [
    'headerActive' => 'showComoParticipar'
    ]);
})->setName('showComoParticipar');

// $app->get('/install[/{extra}]', function ($request, $response, $args) {
//     $actions = new \App\Util\ActionsLoader($this->db);
//     if (!isset($args['extra'])) {
//         $installer = new \App\Util\Installer($this->db);
//         $installer->down();
//         $installer->up();
//         $installer->populate();
//         $loader = new \App\Util\LocalitiesLoader($this->db);
//         $loader->up();
//     } else {
//         $actions->down();
//     }
//     $actions->up();
//     return $response->withJSON(['message' => 'instalación exitosa']);
// });

$app->get('/ping', function ($request, $response, $args) {
    if($request->getAttribute('subject')->getType() != 'Annonymous'){
        return $response->withJSON(['message' => 'Pong!' ]);
    }
    return $response->withJSON(['message' => 'Login']);
});

$app->get('/test', function ($request, $response, $args) {
    $options = $this->db->query('App:Option')->get()->toArray();
    return $this->view->render($response, 'test/simple.twig', [
    'text' => $options,
    ]);
    //return $res->withJSON($this->session->get('user'));
});

// views

$app->get('/login', function ($request, $response, $args) {
    if ($request->getAttribute('subject')->getType() != 'Annonymous') {
        return $response->withRedirect('/');
    }
    return $this->view->render($response, 'base/login.twig', [
    'facebookKey' => $this->get('settings')['facebook']['app_id'],
    'googleKey' => $this->get('settings')['recaptcha']['public_key'],
    ]);
})->setName('showLogin');

$app->get('/sign-up', function ($request, $response, $args) {
    if ($request->getAttribute('subject')->getType() != 'Annonymous') {
        return $response->withRedirect('/');
    }
    return $this->view->render($response, 'base/signUp.twig');
})->setName('showSignUp');

$app->get('/complete-sign-up', function ($request, $response, $args) {
    $token = $request->getQueryParam('token');
    $pending = $this->db->query('App:PendingUser')
    ->where('token', $token)
    ->first();
    if (is_null($pending)) {
        return $response->withRedirect('/');
    }
    return $this->view->render($response, 'base/completar-registro.twig', [
    'activation_key' => $token,
    ]);
})->setName('showCompleteSignUp');

$app->get('/update-email/{usr}/{tkn}', 'userAction:updateEmail')->setName('runUpdUserEma');

// api

$app->get('/category', 'miscAction:getCategories');
$app->get('/region', 'miscAction:getRegions');
$app->get('/region/{reg}/department', 'miscAction:getDepartments');
$app->get('/department/{dep}/locality', 'miscAction:getLocalities');
$app->get('/locality/{loc}', 'miscAction:getLocality');

$app->get('/option/{opt}', 'adminAction:getOption');
$app->post('/option/{opt}', 'adminAction:postOption');
$app->post('/user/{usr}/verified-dni', 'adminAction:postVerifiedDni')->setName('runUpdUserDniVer');
$app->put('/project/{pro}/notes', 'projectAction:putNotes')->setName('putProNot');
$app->post('/user/role', 'userAction:postRole')->setName('runUpdUsrRol');
$app->get('/admin/matriz/{opt}', 'adminAction:getMatriz')->setName('getMat');

$app->get('/user/{usr}', 'userAction:getOne')->setName('getUser');
$app->get('/user', 'userAction:get')->setName('getUsrs');
$app->post('/user/{usr}/profile', 'userAction:postProfile')->setName('runUpdUserPro');
$app->post('/user/{usr}/public-profile', 'userAction:postPublicProfile')->setName('runUpdUserPub');
$app->post('/user/{usr}/dni', 'userAction:postDni')->setName('runUpdUserDni');
$app->get('/user/{usr}/dni', 'userAction:getDniFile')->setName('getUsrDni');
$app->post('/user/{usr}/pending-email', 'userAction:postPendingEmail')->setName('runUpdUserPem');

$app->get('/group/{gro}', 'groupAction:getOne')->setName('getGroup');
$app->get('/group/{gro}/user', 'groupAction:getUsuarios')->setName('getGroUsr');
$app->post('/group', 'groupAction:post')->setName('runCreGro');
$app->post('/group/{gro}', 'groupAction:patch')->setName('runUpdGro');
$app->post('/group/{gro}/invitation', 'groupAction:postInvitation')->setName('runCreGroInv');
$app->post('/group/{gro}/request', 'groupAction:postRequest')->setName('runCreGroReq');
$app->post('/group/{gro}/letter', 'groupAction:postLetter')->setName('runUpdGroLet');
$app->post('/group/{gro}/agreement', 'groupAction:postAgreement')->setName('runUpdGroAgr');
$app->get('/group/{gro}/letter', 'groupAction:getLetterFile')->setName('getGroLet');
$app->get('/group/{gro}/agreement', 'groupAction:getAgreementFile')->setName('getGroAgr');
$app->put('/group/{gro}/second/{usr}', 'groupAction:putSecond')->setName('runCreGroSec');
$app->delete('/group/{gro}/user/{usr}', 'groupAction:deleteUser')->setName('runDelGroUsr');
$app->delete('/group/{gro}/second/{usr}', 'groupAction:deleteSecond')->setName('runDelGroSec');
$app->delete('/group/{gro}', 'groupAction:delete')->setName('delGro');

$app->post('/group/accept-inv/{inv}', 'groupAction:postUserFromInvitation')->setName('runCreGroUsrInv');
$app->post('/group/accept-req/{inv}', 'groupAction:postUserFromRequest')->setName('runCreGroUsrReq');

$app->get('/project', 'projectAction:get')->setName('lisPro');
$app->get('/project/{pro}', 'projectAction:getOne')->setName('getPro');
$app->post('/project', 'projectAction:post')->setName('runCrePro');
$app->post('/project/{pro}', 'projectAction:patch')->setName('runUpdPro');
// $app->delete('/project/{pro}', 'projectAction:delete')->setName('delPro');
$app->post('/project/{pro}/vote', 'projectAction:postVote')->setName('runCreProVot');
$app->post('/project/{pro}/comment', 'projectAction:postComment')->setName('runCreProCom');
$app->post('/comment/{com}/reply', 'projectAction:postReply')->setName('runCreProRep');
$app->post('/comment/{com}/vote', 'projectAction:postCommentVote')->setName('runCreComVot');
$app->get('/project/{pro}/comment', 'projectAction:getComments')->setName('getProCom');

$app->post('/login', 'sessionAction:formLocalLogin')->setName('runLogin');

$app->post('/pending-user', 'userAction:postPendingUser')->setName('runNewPendingUser');
$app->post('/reset-password', 'userAction:postPasswordReset')->setName('runResetPassword');
$app->get('/complete-restore/{usr}/{tkn}', function ($request, $response, $args) {
    $usr = $args['usr'];
    $tkn = $args['tkn'];
    $pending = $this->db->query('App:User')
    ->where('token', $tkn)
    ->first();
    if (is_null($pending)) {
        return $response->withRedirect('/');
    }
    return $this->view->render($response, 'base/completar-password.twig', [
    'activation_key' => $tkn,
    'user' => $usr
    ]);
})->setName('runPassReset');
$app->post('/restore-password/{usr}', 'userAction:postPasswordRestore')->setName('runRestorePassword');
$app->post('/user', 'userAction:post')->setName('runNewUser');

$app->post('/project/{pro}/picture', 'projectAction:postPicture')->setName('runUpdProPic');
$app->get('/project/{pro}/picture', function ($request, $response, $params) {
    $path = 'project/'.$this->helper->getSanitizedId('pro', $params).'.jpg';
    if (!$this->filesystem->has($path)) {
        throw new \App\Util\Exception\AppException(
        'El documento no se encuentra almacenado',
        404
        );
    }
    return $response
    ->withBody(new \Slim\Http\Stream($this->filesystem->readStream($path)))
    ->withHeader('Content-Type', $this->filesystem->getMimetype($path));
})->setName('getProPic');

// guille

// $app->get('/testing', function ($req, $res, $arg) {
//     return $this->view->render($res, 'node/vote/showVote.twig', [
//     ]);
// });

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

// $app->group('/node/create', function () {
//     $this->get('', function ($req, $res, $arg) {
//         return $this->view->render($res, 'node/create.twig', [
//         ]);
//     });
//     $this->get('/[{path:.*}]', function ($req, $res, $arg) {
//         return $this->view->render($res, 'node/create.twig', [
//         ]);
//     });
// });

$app->group('/panel', function () {
    $this->get('', function ($req, $res, $arg) {
        return $this->view->render($res, 'ingenia/panel/userPanel.twig', []);
    })->setName('showPanel');
    $this->get('/[{path:.*}]', function ($req, $res, $arg) {
        return $this->view->render($res, 'ingenia/panel/userPanel.twig', [
        ]);
    });
});

$app->get('/administracion[/{path:.*}]', function ($req, $res, $arg) {
    $subject = $req->getAttribute('subject');
    if (!$this->authorization->checkPermission($subject, 'retOpt')) {
        throw new UnauthorizedException();
    }
    return $this->view->render($res, 'ingenia/admin/adminPanel.twig', []);
})->setName('showAdminPanel');

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

$app->group('/usuario', function () {
    $this->get('/{usr}', function($request, $response, $params){
        $usuario = $this->helper->getEntityFromId(
        'App:User', 'usr', $params, ['groups.project']
        );
        $usuario->addVisible(['groups']);
        return $this->view->render($response, 'ingenia/user/showUser.twig', [
        'user' => $usuario,
        ]);
    })->setName('showProfile');
});

$app->get('/project/{pro}/print', function ($request, $response, $params) {
    $subject = $request->getAttribute('subject');
    $proyecto = $this->helper->getEntityFromId(
    'App:Project', 'pro', $params, ['group']
    );
    if (!$this->authorization->checkPermission($subject, 'updPro', $proyecto)) {
        throw new UnauthorizedException();
    }
    // var_dump($proyecto->organization['locality_id']);
    // $orgLoc = null;
    // if( $proyecto->organization != null ){
    //     $orgLoc = $this->db->query('App:Locality')->where('id', $proyecto->organization['locality_id'])->first();
    // }
    return $this->view->render($response, 'ingenia/project/printProject.twig', [
    'project' => $proyecto,
    // 'orgLoc' => $orgLoc
    ]);
})->setName('printPro');

$app->group('/proyecto', function () {
    $this->get('/{pro}', function($request, $response, $params) {
        $subject = $request->getAttribute('subject');
        $proyecto = $this->helper->getEntityFromId(
        'App:Project', 'pro', $params, ['category']
        );
        if ($subject->getType() == 'User') {
            $voted = !is_null(
            $proyecto->voters()
            ->where('user_id', $subject->getExtra()['user_id'])
            ->first()
            );
        } else {
            $voted = false;
        }
        $proyecto->addVisible(['category_id']);
        // return $response->withJSON($proyecto->toArray());
        return $this->view->render($response, 'ingenia/project/showProject.twig', [
        'project' => $proyecto,
        'voted' => $voted,
        ]);
    })->setName('showProject');
    $this->get('/{pro}/[{path:.*}]', function($request, $response, $params){
        $proyecto = $this->helper->getEntityFromId(
        'App:Project', 'pro', $params
        );
        $proyecto->addVisible(['goals', 'schedule', 'budget','category_id']);
        // return $response->withJSON($proyecto->toArray());
        return $this->view->render($response, 'ingenia/project/showProject.twig', [
        'project' => $proyecto,
        ]);
    });
});

// $app->get('/proyecto/{pro}', function($request, $response, $params){
//     $proyecto = $this->helper->getEntityFromId(
//     'App:Project', 'pro', $params
//     );
//     $proyecto->addVisible(['goals', 'schedule', 'budget','category_id']);
//     // return $response->withJSON($proyecto->toArray());
//     return $this->view->render($response, 'ingenia/project/showProject.twig', [
//     'project' => $proyecto,
//     ]);
// })->setName('shwPro');

// $app->get('/testemail1', function ($req, $res, $arg) {
//     return $this->view->render($res, 'emails/completeRegister.twig', [
//     'url' => 'https://virtuagora.org'
//     ]);
// });

// $app->get('/testemail2', function ($req, $res, $arg) {
//     return $this->view->render($res, 'emails/sendInvitation.twig', [
//     'url' => 'https://virtuagora.org',
//     'team' => 'Nombre de un equipo genial',
//     'comment' => 'Hola! Me gustaria que seas parte de mi equipo ingenia! Besos!'
//     ]);
// });

// $app->get('/testing3', function ($req, $res, $arg) {
//     return $this->view->render($res, 'test3.twig', [
//     ]);
// });


// $app->post('/testing/comment', 'testingAction:commentNodeAction')->setName('commentNode');