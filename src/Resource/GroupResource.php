<?php

namespace App\Resource;

use App\Util\Exception\AppException;
use Carbon\Carbon;

class GroupResource extends Resource
{
    public function retrieveSchema($options = [])
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'description' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 1000,
                ],
                'year' => [
                    'type' => 'integer',
                    'minimum' => 1900,
                    'maximun' => 2018,
                ],
                'previous_editions' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'integer',
                        'minimum' => 2000,
                        'maximun' => 2017,
                    ],
                ],
                'parent_organization' => [
                    'oneOf' => [
                        [
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                    'maxLength' => 50,
                                ],
                                'topics' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'string',
                                        'minLength' => 1,
                                        'maxLength' => 100,
                                    ],
                                ],
                                'topic_other' => [
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
                                'web' => [
                                    'oneOf' => [
                                        [
                                            'type' => 'string',
                                            'minLength' => 1,
                                            'maxLength' => 100,
                                        ], [
                                            'type' => 'null',
                                        ],
                                    ],
                                ],
                                'facebook' => [
                                    'oneOf' => [
                                        [
                                            'type' => 'string',
                                            'minLength' => 1,
                                            'maxLength' => 100,
                                        ], [
                                            'type' => 'null',
                                        ],
                                    ],
                                ],
                                'telephone' => [
                                    'oneOf' => [
                                        [
                                            'type' => 'string',
                                            'minLength' => 1,
                                            'maxLength' => 20,
                                        ], [
                                            'type' => 'null',
                                        ],
                                    ],
                                ],
                                'email' => [
                                    'oneOf' => [
                                        [
                                            'type' => 'string',
                                            'format' => 'email',
                                        ], [
                                            'type' => 'null',
                                        ],
                                    ],
                                ],
                            ],
                            'required' => [
                                'name', 'topics', 'topic_other', 'locality_id', 'web', 
                                'locality_other', 'facebook', 'telephone', 'email',
                            ],
                            'additionalProperties' => false,
                        ], [
                            'type' => 'null',
                        ],
                    ],
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
                'web' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 100,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
                'facebook' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 100,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
                'telephone' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 20,
                ],
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
            ],
            'required' => [
                'name', 'description', 'year', 'previous_editions', 'parent_organization',
                'locality_id', 'locality_other', 'web', 'facebook', 'telephone', 'email',
            ],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function createOne($subject, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);
        
        $localidad = $this->db->query('App:Locality')->findOrFail($data['locality_id']);
        
        $user = $this->helper->getUserFromSubject($subject, ['groups']);
        if (count($user->groups)) {
            throw new AppException('Ya pertenece a un equipo');
        }
        if (count($user->pending_tasks)) {
            throw new AppException('Aún debe completar su perfil de usuario');
        }
        
        $deadline = Carbon::parse($this->options->getAutoloaded()['deadline']);
        $today = Carbon::now();
        if ($today->gt($deadline)) {
            throw new AppException('Application period is over');
        }
        
        $subGr = $this->db->new('App:Subject');
        $subGr->display_name = $data['name'];
        $subGr->img_type = 0;
        $subGr->img_hash = md5($data['email']);
        $subGr->type = 'Group';
        $subGr->save();
        
        $group = $this->db->new('App:Group');
        $group->name = $data['name'];
        $group->description = $data['description'];
        $group->year = $data['year'];
        $group->previous_editions = $data['previous_editions'];
        $group->parent_organization = $data['parent_organization'];
        $group->web = $data['web'];
        $group->facebook = $data['facebook'];
        $group->telephone = $data['telephone'];
        $group->email = $data['email'];
        $group->locality_id = $data['locality_id'];
        if ($localidad->custom && isset($data['locality_other'])) {
            $project->locality_other = $data['locality_other'];
        }
        $group->subject()->associate($subGr);
        $group->save();
        
        $group->users()->attach($user->id, ['relation' => 'responsable']);
        
        $this->db->table('subject_role')->insert([
        'role_id' => 'group',
        'subject_id' => $subGr->id,
        ]);
        
        return $group;
    }
    
    public function updateOne($subject, $group, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);
        $localidad = $this->db->query('App:Locality')->findOrFail($data['locality_id']);
        $deadline = Carbon::parse($this->options->getAutoloaded()['deadline']);
        $today = Carbon::now();
        if ($today->gt($deadline)) {
            throw new AppException('Application period is over');
        }
        $group->name = $data['name'];
        $group->description = $data['description'];
        $group->year = $data['year'];
        $group->previous_editions = $data['previous_editions'];
        $group->parent_organization = $data['parent_organization'];
        $group->web = $data['web'];
        $group->facebook = $data['facebook'];
        $group->telephone = $data['telephone'];
        $group->email = $data['email'];
        $group->locality_id = $data['locality_id'];
        if ($localidad->custom && isset($data['locality_other'])) {
            $project->locality_other = $data['locality_other'];
        }
        $group->save();
        return $group;
    }
    
    public function acceptInvitation($subject, $invitation)
    {
        $user = $this->helper->getUserFromSubject($subject, ['groups']);
        // $invitation = $this->db->query('App:Invitation')->where([
        //     'id' => $invId,
        //     'state' => 'pending',
        //     'user_id' => $user->id,
        // ])->firstOrFail();
        if (count($user->groups)) {
            throw new AppException('User already has a group');
        }
        if (count($user->pending_tasks)) {
            throw new AppException('Incomplete user');
        }
        $invitation->state = 'accepted';
        $invitation->save();
        $user->groups()->syncWithoutDetaching([
            $invitation->group_id => ['relation' => 'miembro']
        ]);
        $group = $this->db->query('App:Group')
            ->withCount('users')
            ->find($invitation->group_id);
        if ($group->users_count > 5) {
            $group->full_team = true;
            $group->save();
        }
    }
    
    public function inviteUser($subject, $group, $data)
    {
        $v = $this->validation->fromSchema([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'format' => 'email',
                ],
                'comment' => [
                    [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 250,
                    ], [
                        'type' => 'null',
                    ],
                ],
            ],
            'required' => ['email'],
            'additionalProperties' => false,
        ]);
        $v->assert($data);
        $email = $data['email'];
        $countInvit = $group->invitations()->count();
        if ($countInvit > 10) {
            throw new AppException('Invitations limit reached');
        }
        $prevInvit = $this->db->query('App:Invitation')->where([
            'email' => $email,
            'group_id' => $group->id
        ])->first();
        if (isset($prevInvit)) {
            throw new AppException('User already invited');
        }
        $user = $this->db->query('App:User')->where('email', $email)->first();
        if (isset($user)) {
            $invitation = $this->db->new('App:Invitation', [
                'user_id' => $user->id,
                'group_id' => $group->id
            ]);
        } else {
            $invitation = $this->db->new('App:Invitation', [
                'group_id' => $group->id
            ]);
            $pending = $this->identity->createPendingUser('local', [
                'identifier' => $email,
            ]);

            $mailSub = 'Te han invitado a participar de Ingenia';
            $link = $this->helper->pathFor('showCompleteSignUp', true, [], [
                'token' => $pending->token,
            ]);
            $mailMsg = $this->view->fetch('emails/sendInvitation.twig', [
                'url' => $link,
                'comment' => isset($data['comment'])? $data['comment']: '',
            ]);
            $this->logger->info($link);
            $this->mailer->sendMail($mailSub, $pending->identifier, $mailMsg, 'text/html');
        }
        $invitation->state = 'pending';
        $invitation->email = $email;
        if (isset($data['comment'])) {
            $invitation->comment = $data['comment'];
        }
        $invitation->save();
        return $invitation;
    }

    public function requestInvitation($subject, $group, $data)
    {
        $v = $this->validation->fromSchema([
            'type' => 'object',
            'properties' => [
                'comment' => [
                    [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 250,
                    ], [
                        'type' => 'null',
                    ],
                ],
            ],
            'additionalProperties' => false,
        ]);
        $v->assert($data);
        $user = $this->helper->getUserFromSubject($subject, ['groups']);
        if (count($user->groups)) {
            throw new AppException('Ya pertenece a un equipo');
        }
        if (count($user->pending_tasks)) {
            throw new AppException('Aún debe completar su perfil de usuario');
        }
        $invitation = $this->db->query('App:Invitation')->firstOrNew([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $invitation->state = 'requested';
        if (isset($data['comment'])) {
            $invitation->comment = $data['comment'];
        }
        $invitation->save();
        return $invitation;
    }

    public function acceptRequest($subject, $invitation)
    {
        $user = $invitation->user;
        if (count($user->groups)) {
            throw new AppException('User already has a group');
        }
        $invitation->state = 'accepted';
        $invitation->save();
        $user->groups()->syncWithoutDetaching([
            $invitation->group_id => ['relation' => 'miembro'],
        ]);
        $group = $this->db->query('App:Group')
            ->withCount('users')
            ->find($invitation->group_id);
        if ($group->users_count > 5) {
            $group->full_team = true;
            $group->save();
        }
    }

    public function updateLetter($subject, $group, $file)
    {
        if (is_null($group->project->organization)) {
            throw new AppException('El proyecto no se realiza con una organización');
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
        $group->uploaded_letter = true;
        $group->save();
        $fileStrm = $file->getStream()->detach();
        $this->filesystem->putStream(
        'avales/'.$group->id.'.'.$allowedMimes[$fileMime],
        $fileStrm
        );
        if (is_resource($fileStrm)) {
            fclose($fileStrm);
        }
    }
    
    public function updateAgreement($subject, $group, $file)
    {
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
        $group->uploaded_agreement = true;
        $group->save();
        $fileStrm = $file->getStream()->detach();
        $this->filesystem->putStream(
        'acuerdos/'.$group->id.'.'.$allowedMimes[$fileMime],
        $fileStrm
        );
        if (is_resource($fileStrm)) {
            fclose($fileStrm);
        }
    }

    public function postCompleted($subject, $group)
    {
        if ($group->public) {
            throw new AppException('El proyecto ya está publicado');
        }
        if (!$group->uploaded_agreement) {
            throw new AppException('Debe cargar el acuerdo de conformidad primero');
        }
        if (!$group->uploaded_letter) {
            throw new AppException('Debe cargar la carta aval de la organización primero');
        }
        if (!$group->full_team) {
            throw new AppException('El equipo no cuenta con la cantidad suficiente de integrantes');
        }
        if (!$group->second_in_charge) {
            throw new AppException('El equipo debe contar con un co-responsable');
        }
        $group->public = true;
        $group->save();
    }
}