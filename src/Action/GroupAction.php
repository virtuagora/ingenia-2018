<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class GroupAction
{
    protected $groupResource;
    protected $representation;

    public function __construct($groupResource, $representation)
    {
        $this->groupResource = $projectResource;
        $this->representation = $representation;
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
}
