<?php

namespace App\Util\IdentityProvider;

class LocalIdentityProvider
{
    protected $db;
    protected $validation;

    public function __construct($db, $validation)
    {
        $this->db = $db;
        $this->validation = $validation;
    }

    public function makeIdentifiers($options)
    {
        $v = $this->validation->fromSchema([
            'email' => [
                'type' => 'string',
                'format' => 'email',
            ],
            'password' => [
                'type' => 'string',
                'minLength' => 1,
                'maxLength' => 250,
            ],
        ]);
        $v->assert($options);
        return [
            'email' => $options['email'],
            'password' => $options['password'],
        ];
    }

    public function retrieveUser($data)
    {
        $user = $this->db->query('App:User')->where('email', $data['email'])->first();
        if (isset($user)) { //&& password_verify($data['password'], $user->password)) {
            return $user;
        } else {
            return null;
        }
    }

    public function makeRegistrationToken($data)
    {
        return null;
    }
}
