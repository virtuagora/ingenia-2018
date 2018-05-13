<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;
use Slim\Http\Stream;

class UserAction
{
    protected $userResource;
    protected $representation;
    protected $helper;
    protected $authorization;
    protected $recaptcha;
    protected $router;

    public function __construct(
        $userResource, $representation, $helper, $authorization, $recaptcha, $router
    ) {
        $this->userResource = $userResource;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->recaptcha = $recaptcha;
        $this->router = $router;
    }

    public function get($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $pagParams = $this->pagination->getParams($request, [
            'dni_state' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'roles' => [
                'type' => 'string',
            ],
            's' => [
                'type' => 'string',
            ],
        ]);
        $resultados = $this->userResource->retrieve($pagParams);
        $resultados->setUri($request->getUri());
        if ($this->authorization->checkPermission($subject, 'retDni')) {
            $resultados->makeVisible([
                'dni',
            ]);
        }
        return $this->pagination->renderResponse($response, $resultados);
    }

    // GET /user/{usr}
    public function getOne($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $usuario = $this->helper->getEntityFromId(
            'App:User', 'usr', $params, ['groups.project.category', 'invitations']
        );
        if ($this->authorization->checkPermission($subject, 'retUsrFull', $usuario)) {
            $visible = [
                'groups', 'birthday', 'gender', 'address', 'telephone',
                'neighbourhood', 'referer', 'email', 'invitations',
                'referer_other',
            ];
        } else {
            $visible = ['groups'];
        }
        $usuario->addVisible($visible);
        return $response->withJSON($usuario->toArray());
    }

    public function post($request, $response, $params)
    {
        $user = $this->userResource->createOne(null, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'User registered succefully',
            'status' => 200,
            'user' => $user->toArray(),
            'template' => 'base/confirmSignUp.twig',
        ]);
    }

    public function postPendingUser($request, $response, $params)
    {
        $data = $request->getParsedBody();
        if (!isset($data['recaptcha'])) {
            throw new AppException('CAPTCHA no recibido');
        }
        $gRecaptchaResponse = $data['recaptcha'];
        unset($data['recaptcha']);
        $captchaResp = $this->recaptcha->verify($gRecaptchaResponse);
        if (!$captchaResp->isSuccess()) {
            throw new AppException('Verificación de CAPTCHA inválida');
        }
        $this->userResource->createPendingUser(null, $data);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Pending user created',
            'status' => 200,
            'template' => 'base/emailSended.twig',
        ]);
    }

    public function postPublicProfile($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updUsrProfile', $user)) {
            throw new UnauthorizedException();
        }
        $user = $this->userResource->updatePublicProfile($subject, $user, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Profile updated succefully',
            'status' => 200,
            'user' => $user->toArray(),
        ]);
    }

    public function postPendingEmail($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updUsrProfile', $user)) {
            throw new UnauthorizedException();
        }
        $this->userResource->updatePendingEmail($subject, $user, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Se envió un correo con los pasos para actulizar dirección',
            'status' => 200,
        ]);
    }

    public function updateEmail($request, $response, $params)
    {
        $user = $this->helper->getEntityFromId('App:User', 'usr', $params);
        $data = [
            'token' => $this->helper->getSanitizedStr('tkn', $params),
        ];
        $this->userResource->updateEmail($user, $data);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Email actualizado',
            'status' => 200,
        ]);
    }

    public function postPasswordReset($request, $response, $params)
    {
        $this->userResource->resetPassword($request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Se envió un correo con los pasos para recuperar clave',
            'status' => 200,
        ]);
    }

    public function postPasswordRestore($request, $response, $params)
    {
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        $this->userResource->restorePassword($user, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Clave reiniciada',
            'status' => 200,
        ]);
    }

    public function putClave($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updUsrPas', $user)) {
            throw new UnauthorizedException();
        }
        $this->userResource->updateEmail($user, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Clave actualizada',
            'status' => 200,
        ]);
    }

    public function postProfile($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updUsrProfile', $user)) {
            throw new UnauthorizedException();
        }
        $user = $this->userResource->updateProfile($subject, $user, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Profile updated succefully',
            'status' => 200,
            'user' => $user->toArray(),
        ]);
    }

    public function postDni($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updUsrDni', $user)) {
            throw new UnauthorizedException();
        }
        $atributos = $request->getParsedBody();
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->userResource->updateDni($subject, $user, $atributos, $archivo);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
        // return $this->representation->returnMessage($request, $response, [
        //     'message' => 'DNI loaded succefully',
        //     'status' => 200,
        // ]);
    }

    public function getDniFile($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $usuario = $this->helper->getEntityFromId(
            'App:User', 'usr', $params
        );
        if (!$this->authorization->checkPermission($subject, 'retUsrFull', $usuario)) {
            throw new UnauthorizedException();
        }
        $fileData = $this->userResource->getDniFile($usuario);
        return $response->withBody(new Stream($fileData['strm']))
            ->withHeader('Content-Type', $fileData['mime']);
    }
}
