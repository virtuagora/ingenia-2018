<?php

namespace App\Resource;

use App\Util\Exception\AppException;

class UserResource extends Resource
{
    public function retrieveSchema($options = [])
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'names' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 25,
                ],
                'surnames' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 25,
                ],
                'password' => [
                    'type' => 'string',
                    'minLength' => 4,
                    'maxLength' => 250,
                ],
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
                'activation_key' => [
                    'type' => 'string',
                    'minLength' => 10,
                    'maxLength' => 100,
                ],
            ],
            'required' => ['names', 'surnames', 'password', 'activation_key'],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function createOne($subject, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);
        $pending = $this->db->query('App:PendingUser')
            ->where('provider', 'local')
            ->where('activation_key', $data['activation_key'])
            ->first();
        if (is_null($pending)) {
            throw new AppException('Datos incorrectos', 400);
        }
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

    public function createPendingUser($subject, $data)
    {
        $v = $this->validation->fromSchema([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
            ],
            'required' => ['email'],
            'additionalProperties' => false,
        ]);
        $v->assert($data);

        $user = $this->db->query('App:User')
            ->where('email', $data['email'])
            ->first();
        if (isset($user)) {
            throw new AppException('Email already registered', 400);
        }

        // TODO si el pending user ya existe reenviar email
        $pending = $this->db->new('App:PendingUser');
        $pending->provider = 'local';
        $pending->identifier = $data['email'];
        $pending->activation_key = bin2hex(random_bytes(10));
        $pending->save();
        
        $mailMsg = 'Accede con ' . $pending->activation_key;
        $mailSub = 'Registro Virtuágora';
        $this->logger->info($mailMsg);
        $this->mailer->sendMail($mailSub, $data['email'], $mailMsg);
    }
}
