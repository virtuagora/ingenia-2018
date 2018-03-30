<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class UserAction
{
    protected $userResource;
    protected $representation;
    protected $helper;
    protected $authorization;

    public function __construct($userResource, $representation, $helper, $authorization)
    {
        $this->userResource = $userResource;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
    }

    // GET /usuario/{usr}
    public function getOne($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $usuario = $this->helper->getEntityFromId(
            'App:User', 'usr', $params, ['groups']
        );
        $usuario->addVisible(['groups']);
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
        $captchaResp = $recaptcha->verify($gRecaptchaResponse);
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
        return $this->representation->returnMessage($request, $response, [
            'message' => 'DNI loaded succefully',
            'status' => 200,
        ]);
    }
}
