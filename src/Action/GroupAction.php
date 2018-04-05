<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class GroupAction
{
    protected $groupResource;
    protected $representation;
    protected $helper;
    protected $authorization;

    public function __construct($groupResource, $representation, $helper, $authorization)
    {
        $this->groupResource = $groupResource;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
    }

    // GET /grupo/{gro}
    public function getOne($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params
        );
        if ($this->authorization->checkPermission($subject, 'retGroFull', $group)) {
            $group->addVisible(['telephone', 'email']);
        }
        return $response->withJSON($group->toArray());
    }

    public function post($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'creGro')) {
            throw new UnauthorizedException();
        }
        $group = $this->groupResource->createOne($subject, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'group created succefully',
            'status' => 200,
            'group' => $group->toArray(),
        ]);
    }

    public function postInvitation($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'creGroInvit', $group)) {
            throw new UnauthorizedException();
        }
        $invitation = $this->groupResource->inviteUser($subject, $group, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Invitación enviada',
            'status' => 200,
            'invitation' => $invitation->toArray(),
        ]);
    }

    public function postAgreement($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updGroAgreement', $group)) {
            throw new UnauthorizedException();
        }
        $atributos = $request->getParsedBody();
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->groupResource->updateAgreement($subject, $group, $archivo);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
    }

    public function postLetter($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updGroLetter', $group)) {
            throw new UnauthorizedException();
        }
        $atributos = $request->getParsedBody();
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->groupResource->updateLetter($subject, $group, $archivo);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
    }

    public function postCompleted($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updGroCompleted', $group)) {
            throw new UnauthorizedException();
        }
        $this->groupResource->updateAgreement($subject, $group);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Proyecto enviado para participar',
            'status' => 200,
        ]);
    }
}
