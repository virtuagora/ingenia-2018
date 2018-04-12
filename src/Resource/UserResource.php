<?php

namespace App\Resource;

use Carbon\Carbon;
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
        if (isset($user->email)) {
            $this->db->query('App:Invitation')
                ->where('email', $user->email)
                ->update(['user_id' => $user->id]);
        }
        return $user;
    }

    public function createPendingUser($subject, $data) // el subject sirve para invitaciones
    {
        $pending = $this->identity->createPendingUser('local', $data);
        $mailMsg = 'Accede con ' . $pending->token;
        $mailSub = 'Registro Virtuágora';
        $this->logger->info($mailMsg);
        $this->mailer->sendMail($mailSub, $pending->identifier, $mailMsg);
    }

    public function updateProfile($subject, $user, $data)
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'birthday' => [
                    'type' => 'string',
                    'pattern' => '^\d{4}-\d{2}-\d{2}$',
                ],
                'gender' => [
                    'type' => 'string',
                    'enum' => ['Hombre', 'Mujer', 'HombreTrans', 'MujerTrans', 'Intersex'],
                ],
                'address' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 300,
                ],
                'telephone' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 20,
                ],
                'locality_id' => [
                    'type' => 'integer',
                    'minimum' => 1,
                ],
                'locality_other' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 250,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
                'neighbourhood' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'referer' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 250,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
                'referer_other' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 250,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
            ],
            'required' => [
                'birthday', 'gender', 'address', 'telephone', 'neighbourhood',
                'locality_id', 'locality_other', 'referer', 'referer_other'
            ],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        $localidad = $this->db->query('App:Locality')->findOrFail($data['locality_id']);
        $user->birthday = $data['birthday'];
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->telephone = $data['telephone'];
        $user->neighbourhood = $data['neighbourhood'];
        $user->referer = $data['referer'];
        $user->referer_other = $data['referer_other'];
        $user->locality_id = $data['locality_id'];
        if ($localidad->custom && isset($data['locality_other'])) {
            $user->locality_other = $data['locality_other'];
        }
        $user->save();
        return $user;
    }

    public function updatePublicProfile($subject, $user, $data)
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'bio' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 300,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
            ],
            'required' => [
                'bio',
            ],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        $user->bio = $data['bio'];
        $user->save();
        return $user;
    }

    public function updatePendingEmail($subject, $user, $data)
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                    'minLength' => 1,
                    'maxLength' => 300,
                ],
            ],
            'required' => [
                'email',
            ],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        $user->pending_email = $data['email'];
        $user->token = 'email:'.bin2hex(random_bytes(10));
        $user->token_expiration = Carbon::tomorrow();
        $user->save();
        $link = $this->helper->pathFor('runUpdUserEma', true, [
            'usr' => $user->id,
            'tkn' => $user->token,
        ]);
        $mailMsg = 'Link de cambio ' . $link;
        $mailSub = 'Registro Virtuágora';
        $this->logger->info($mailMsg);
        $this->mailer->sendMail($mailSub, $data['email'], $mailMsg);
    }

    public function updateEmail($user, $data)
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'pattern' => '^email:[A-z0-9]+$',
                    'minLength' => 1,
                    'maxLength' => 50,
                ],
            ],
            'required' => [
                'token',
            ],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        if ($user->token != $data['token']) {
            throw new AppException('Token inválido');
        }
        if ($user->token_expiration->lt(Carbon::now())) {
            throw new AppException('Token expirado');
        }
        $other = $this->db->query('App:User')->where('email', $user->pending_email)->first();
        if (isset($other)) {
            throw new AppException('Esa dirección de email ya está registrada');
        }
        $user->email = $user->pending_email;
        $user->pending_email = null;
        $user->token = null;
        $user->token_expiration = null;
        $user->save();
    }

    public function updateDni($subject, $user, $data, $file)
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'dni' => [
                    'type' => 'string',
                    'minLength' => 7,
                    'maxLength' => 9,
                ],
            ],
            'required' => ['dni'],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        if ($user->verified_dni) {
            throw new AppException('No puede actualizar su DNI una vez verificado');
        }
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new AppException('Hubo un error con el archivo recibido');
        }
        $fileMime = $file->getClientMediaType();
        $allowedMimes = [
            'application/pdf' => 'pdf',
            'invalid/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
        ];
        if (!isset($allowedMimes[$fileMime])) {
            throw new AppException('Tipo de documento inválido');
        }
        $dniBlacklist = $this->options->getOption('dni-blacklist');
        $user->dni = $data['dni'];
        if (in_array($user->dni, $dniBlacklist->value)) {
            $user->verified_dni = false;
        }
        $user->save();
        $fileStrm = $file->getStream()->detach();
        $this->filesystem->putStream('dni/'.$user->id.'.'.$allowedMimes[$fileMime], $fileStrm);
        if (is_resource($fileStrm)) {
            fclose($fileStrm);
        }
    }
}
