<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class ProjectAction
{
    protected $projectResource;
    protected $representation;

    public function __construct($projectResource, $representation)
    {
        $this->projectResource = $projectResource;
        $this->representation = $representation;
    }

    public function post($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'crePro')) {
            throw new UnauthorizedException();
        }
        $project = $this->projectResource->createOne($subject, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Project created succefully',
            'status' => 200,
            'project' => $project->toArray(),
        ]);
    }
}
