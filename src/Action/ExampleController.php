<?php namespace App\Action;

final class ExampleController extends AbstractController
{
    public function greet($request, $response, $params)
    {
        $name = isset($params['name'])? $params['name']: 'stranger';
        return $this->view->render($response, 'master.twig', [
            'name' => $name
        ]);
    }
    
    public function sendMail($request, $response, $params)
    {
        $this->debugger->addMessage($this->mailer->sendMail('this is a fake email', ['foo@bar.com'], 'fake delivery en developer mode.'));
        return $this->view->render($response, 'master.twig', [
            'name' => 'and watch the sended email.'
        ]);
    }
    
    public function queryDB($request, $response, $params)
    {
        $users = $this->db->table('users')->lists('name');
        $this->debugger->addMessage($this->db->table('users')->find(1));
        $this->debugger->addMessage($this->db->query('App:User')->find(2));
        foreach ($users as $u) {
            $this->debugger->addMessage($u);
        }
        return $this->view->render($response, 'master.twig', [
            'name' => 'and watch the users.'
        ]);
    }

    ////

    protected $extensions = [
        //'base' => new UserBaseAction(),
    ];
    protected $strategies = [
        //'fetchOne' => ['base'],
    ];

    public function addExtension($instance, $name, $methods) {
        $this->extensions[$name] = $instance;
        foreach ($methods as $method) {
            if (isset($this->strategies[$method])) {
                $this->strategies[$method][] = $name;
            } else {
                $this->strategies[$method] = [$name];
            }
        }
    }

    public function __call($method, $arguments)
    {
        if (count($arguments) != 3) {
            throw new Exception();
        }
        if (!isset($this->strategies[$method])) {
            throw new Exception();
        }
        $strategyChain = $this->strategies[$method];
        foreach ($strategyChain as $selected) {
            $arguments[1] = call_user_func_array(
                [$this->extensions[$selected], $method],
                $arguments
            );
        }
        return $arguments[1];
    }
}
