<?php

namespace App\Action;

class SessionAction
{
    protected $session;
    protected $identity;
    protected $view;
    protected $db;

    public function __construct($session, $identity, $view, $db)
    {
        $this->session = $session;
        $this->identity = $identity;
        $this->view = $view;
        $this->db = $db;
    }

    public function formLocalLogin($request, $response, $params)
    {
        $result = $this->identity->signIn('local', $request->getParsedBody());
        if ($result['status'] == 'success') {
            $user = $result['user'];
            $group = $user->groups->first();
            $session = $this->session->signIn($user->subject->toDummy([
                'user_id' => $user->id,
                'group' => [
                    'id' => $group->id,
                    'relation' => $group->pivot->relation,
                    'name' => $group->name,
                ],
            ]));
            return $response->withRedirect('/');
        } else {
            return $this->view->render($response, 'base/login.twig', [
                'error' => $result['status'],
            ]);
        }
    }
}
