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
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 100,
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
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 250,
                ],
                'webpage' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'facebook' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 100,
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
                'referer' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 200,
                ],
            ],
            'required' => [
                'name', 'description', 'year', 'previous_editions', 'parent_organization',
                'locality_id', 'webpage', 'facebook', 'telephone', 'email', 'referer'
            ],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function acceptInvitation($subject, $invId)
    {
        $user = $this->helper->getUserFromSubject($subject, ['groups']);
        $invitation = $this->db->query('App:Invitation')->where([
            'id' => $invId,
            'state' => 'pending',
            'user_id' => $user->id,
        ])->firstOrFail();
        if (count($user->groups)) {
            throw new AppException('User already has a group');
        }
        if (count($user->pending_tasks)) {
            throw new AppException('Incomplete user');
        }
        $invitation->state = 'accepted';
        $invitation->save();
        $user->groups()->attach($invitation->group_id, ['relation' => 'miembro']);
    }

    public function inviteUser($subject, $group, $email)
    {
        $v = $this->validation->fromSchema([
            'type' => 'string',
            'format' => 'email',
        ]);
        $v->assert($email);

        $countInvit = $group->invitations()->count();
        if ($countInvit > 10) {
            throw new AppException('Invitations limit reached');
        }

        $prevInvit = $this->db->query('App:Invitation')->where([
            'user_id' => $user->id,
            'group_id' => $group->id
        ])->first();
        if (isset($prevInvit)) {
            throw new AppException('User already invited');
        }

        $user = $this->db->query('App:User')->where('email', $email)->first();
        if (isset($user)) {
            $invitation = $this->db->new('App:Group', [
                'user_id' => $user->id,
                'group_id' => $group->id
            ]);
        } else {
            $invitation = $this->db->new('App:Group', [
                'group_id' => $group->id
            ]);
            $pending = $this->identity->createPendingUser('local', [
                'identifier' => $email,
            ]);
            // $pending->fields = [
            //     'group_id' => $group->id,
            // ];
            // $pending->save();
            $mailMsg = 'Invitado Accede con ' . $pending->token;
            $mailSub = 'Registro Virtuágora';
            $this->logger->info($mailMsg);
            $this->mailer->sendMail($mailSub, $pending->identifier, $mailMsg);
        }
        $invitation->state = 'pending';
        $invitation->email = $email;
        $invitation->save();
    }

    public function createOne($subject, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);

        $localidad = $this->db->query('App:Locality')->findOrFail($data['localidad']);

        $user = $this->helper->getUserFromSubject($subject, ['groups']);
        if (count($user->groups)) {
            throw new AppException('User already has a group');
        }

        $deadline = Carbon::parse($this->settings->getSettings()['deadline']);
        $today = Carbon::now();
        if ($deadline->gt($today)) {
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
        $group->webpage = $data['webpage'];
        $group->facebook = $data['facebook'];
        $group->telephone = $data['telephone'];
        $group->email = $data['email'];
        $group->referer = $data['referer'];
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
}
