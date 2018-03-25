<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class UserAction
{
    protected $userResource;
    protected $representation;

    public function __construct($userResource, $representation)
    {
        $this->userResource = $userResource;
        $this->representation = $representation;
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
        $this->userResource->createPendingUser(null, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Pending user created',
            'status' => 200,
            'template' => 'base/emailSended.twig',
        ]);
    }

    public function postProfile($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'updUsrProfile', $subject)) {
            throw new UnauthorizedException();
        }
        $user = $this->userResource->updateProfile($subject, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Profile updated succefully',
            'status' => 200,
            'user' => $user->toArray(),
        ]);
    }

    public function postDni($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'updUsrDni', $subject)) {
            throw new UnauthorizedException();
        }
        $atributos = $request->getParsedBody();
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->userResource->updateDni($subject, $atributos, $archivo);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'DNI loaded succefully',
            'status' => 200,
        ]);
    }
}
