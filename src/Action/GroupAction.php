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
}
