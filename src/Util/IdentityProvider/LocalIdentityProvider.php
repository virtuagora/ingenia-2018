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
        if (isset($user) && password_verify($data['password'], $user->password)) {
            return $user;
        } else {
            return null;
        }
    }

    public function makeRegistrationToken($data)
    {
        return null;
    }

    public function createPendingUser($data)
    {
        $v = $this->validation->fromSchema([
            'type' => 'object',
            'properties' => [
                'identifier' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
            ],
            'required' => ['identifier'],
            'additionalProperties' => false,
        ]);
        $v->assert($data);
        $user = $this->db->query('App:User')
            ->where('email', $data['identifier'])
            ->first();
        if (isset($user)) {
            throw new AppException('Email already registered');
        }
        $pending = $this->db->query('App:PendingUser')->firstOrNew([
            'provider' => 'local',
            'identifier' => $data['identifier'],
        ]);
        $pending->token = bin2hex(random_bytes(10));
        $pending->save();
        return $pending;
    }

    public function registerUser($data, $pending)
    {
        $subj = $this->db->new('App:Subject');
        $subj->display_name = $data['names'] . ' ' . $data['surnames'];
        $subj->img_type = 0;
        $subj->img_hash = md5($pending->identifier);
        $subj->type = 'User';
        $subj->save();
        $user = $this->db->new('App:User');
        $user->email = $pending->identifier;
        $user->password = $data['password'];
        $user->names = $data['names'];
        $user->surnames = $data['surnames'];
        $user->subject()->associate($subj);
        $user->save();
        $pending->delete();
        return $user;
    }
}
