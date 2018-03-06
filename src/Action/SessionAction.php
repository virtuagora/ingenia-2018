<?php

namespace App\Action;

class SessionAction
{
    protected $session;
    protected $identity;
    protected $view;

    public function __construct($session, $identity, $view)
    {
        $this->session = $session;
        $this->identity = $identity;
        $this->view = $view;
    }

    public function formLocalLogin($request, $response, $params)
    {
        $result = $this->identity->signIn('local', $request->getParsedBody());
        if ($result['status'] == 'success') {
            $user = $result['user'];
            $session = $this->session->signIn($user->subject->toDummy());
            return $response->withRedirect('/');
        } else {
            return $this->view->render($response, 'base/login.twig', [
                'error' => $result['status'],
            ]);
        }
    }
}
