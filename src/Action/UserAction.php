<?php namespace App\Action;

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
        $options = ['template' => 'login.twig'];
        return $this->representation->returnMessage($request, $response, [
            'message' => 'User registered succefully',
            'status' => 200,
            'user' => $user->toArray(),
            'template' => 'base/confirmSignUp.twig',
        ]);
    }

    public function postEmail($request, $response, $params)
    {
        $this->userResource->createPendingUser(null, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Check your email',
            'status' => 200,
            'template' => 'base/emailSended.twig',
        ]);
    }
}
