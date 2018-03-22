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
                'token' => [
                    'type' => 'string',
                    'minLength' => 10,
                    'maxLength' => 100,
                ],
            ],
            'required' => ['names', 'surnames', 'password', 'token'],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function createOne($subject, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);
        $token = $data['token'];
        unset($data['token']);
        $user = $this->identity->registerUser($data, $token);
        return $user;
    }

    public function createPendingUser($subject, $data) // el subject sirve para invitaciones
    {
        $pending = $this->identity->createPendingUser('local', $data);
        $mailMsg = 'Accede con ' . $pending->activation_key;
        $mailSub = 'Registro Virtuágora';
        $this->logger->info($mailMsg);
        $this->mailer->sendMail($mailSub, $pending->identifier, $mailMsg);
    }
}
