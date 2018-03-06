<?php namespace App\Action;

class VotingAction
{
    protected $votingResource;
    protected $representation;

    public function __construct($votingResource, $representation)
    {
        $this->votingResource = $votingResource;
        $this->representation = $representation;
    }

    public function post($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        //$action = $this->action->get('newVoting');
        //this->authorization()->checkPermission($subject, $action)
        $voting = $this->votingResource->createOne($subject, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Voting registered succefully',
            'status' => 200,
            'user' => $voting,
            'template' => 'user.twig',
        ]);
    }
}