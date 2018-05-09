<?php

namespace App\Resource;

use App\Util\Paginator;
use App\Util\Exception\AppException;

class ProjectResource extends Resource
{
    public function retrieveSchema($options = [])
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 250,
                ],
                'abstract' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 2000,
                ],
                'foundation' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 1500,
                ],
                'category_id' => [
                    'type' => 'integer',
                    'minimum' => 1,
                ],
                'previous_work' => [
                    'oneOf' => [
                        [
                            'type' => 'string',
                            'minLength' => 1,
                            'maxLength' => 1000,
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],
                'neighbourhoods' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 100,
                    ],
                ],
                'goals' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 300,
                    ],
                ],
                'schedule' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'date' => [
                                'type' => 'string',
                                'pattern' => '^\d{4}-\d{2}-\d{2}$',
                            ],
                            'activity' => [
                                'type' => 'string',
                                'minLength' => 1,
                                'maxLength' => 300,
                            ],
                        ],
                        'required' => ['date', 'activity'],
                        'additionalProperties' => false,
                    ],
                ],
                'budget' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => [
                                'type' => 'string',
                                'minLength' => 1,
                                'maxLength' => 50,
                            ],
                            'description' => [
                                'type' => 'string',
                                'minLength' => 1,
                                'maxLength' => 300,
                            ],
                            'amount' => [
                                'type' => 'number',
                                'minimum' => 1,
                            ],
                        ],
                        'required' => ['category', 'description', 'amount'],
                        'additionalProperties' => false,
                    ],
                ],
                'organization' => [
                    'oneOf' => [
                        [
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                    'maxLength' => 100,
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
                                'name', 'topics', 'topic_other', 'locality_id',
                                'locality_other', 'web', 'facebook', 'telephone', 'email',
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
            ],
            'required' => [
                'name', 'abstract', 'foundation', 'category_id', 'previous_work',
                'neighbourhoods', 'goals', 'schedule', 'budget', 'organization',
                'locality_id', 'locality_other',
            ],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function retrieve($options)
    {
        $query = $this->db->query('App:Project', ['group']);
        
        if (isset($options['loc'])) {
            $query->where('locality_id', $options['loc']);
        } elseif (isset($options['dep'])) {
            $query->whereHas('locality', function ($q) use ($options) {
                $q->where('department_id', $options['dep']);
            });
        } elseif (isset($options['reg'])) {
            $query->whereHas('locality.department', function ($q) use ($options) {
                $q->where('region_id', $options['reg']);
            });
        }
        if (isset($options['cat'])) {
            $query->where('category_id', $options['cat']);
        }
        if (isset($options['s'])) {
            $filter = $this->helper->generateTrace($options['s']);
            $query->where('trace', 'LIKE', "%$filter%");
        }
        $results = new Paginator($query, $options);
        return $results;
    }

    public function retrieveComments($proId, $options)
    {
        $query = $this->db->query('App:Comment')->where('project_id', $proId);
        if (isset($options['usr'])) {
            $query->where('author_id', $options['usr']);
        }
        $results = new Paginator($query, $options);
        return $results;
    }

    public function createOne($subject, $data)
    {
        $user = $this->helper->getUserFromSubject($subject);
        $group = $user->groups()->with('project')->wherePivot('relation', 'responsable')->first();
        if (is_null($group)) {
            throw new AppException('Debe cargar un equipo primero');
        }
        if (!is_null($group->project)) {
            throw new AppException('El equipo ya posee un proyecto cargado');
        }
        $project = $this->db->new('App:Project');
        return $this->persist($project, $group, $data);
    }

    public function updateOne($subject, $project, $data)
    {
        $group = $project->group;
        return $this->persist($project, $group, $data);
    }

    // TODO comprobar deadline
    public function persist($project, $group, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);

        $localidad = $this->db->query('App:Locality')->findOrFail($data['locality_id']);
        $categoria = $this->db->query('App:Category')->findOrFail($data['category_id']);

        $duplicados = $this->helper->getDuplicatedFields(
            'App:Project', $project, ['name']
        );
        if (!empty($duplicados)) {
            throw new AppException('usedName');
        }

        $totalBudget = 0;
        foreach ($data['budget'] as $item) {
            $totalBudget += $item['amount'];
        }

        $project->name = $data['name'];
        $project->trace = $this->helper->generateTrace($data['name']);
        $project->abstract = $data['abstract'];
        $project->foundation = $data['foundation'];
        $project->previous_work = $data['previous_work'];
        $project->neighbourhoods = $data['neighbourhoods'];
        $project->goals = $data['goals'];
        $project->budget = $data['budget'];
        $project->total_budget = $totalBudget;
        $project->schedule = $data['schedule'];
        $project->organization = $data['organization'];
        $project->category_id = $data['category_id'];
        $project->locality_id = $data['locality_id'];
        $project->locality_other = $localidad->custom ? $data['locality_other'] : null;
        $project->group_id = $group->id;
        $project->save();
        if (is_null($project->organization)) {
            $group->uploaded_letter = true;
            $group->save();
        }
        return $project;
    }

    public function updatePicture($subject, $project, $imgFile)
    {
        if ($imgFile->getError() == UPLOAD_ERR_NO_FILE) {
            throw new AppException('No se envió archivo');
        } elseif ($imgFile->getError() !== UPLOAD_ERR_OK) {
            throw new AppException(
                'Hubo un error con el archivo recibido ('.$imgFile->getError().')'
            );
        }
        $fileMime = $imgFile->getClientMediaType();
        $allowedMimes = [
            'image/jpeg' => 'jpg',
            'image/pjpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];
        if (!isset($allowedMimes[$fileMime])) {
            throw new AppException('Tipo de archivo inválido');
        }
        $imgStrm = $this->image->make($imgFile->getStream()->detach())
            ->fit(1000, 777, function ($constraint) {
                $constraint->upsize();
            })
            ->encode('jpg', 90);
        $this->filesystem->put('project/'.$project->id.'.jpg', $imgStrm);
        if (is_resource($imgStrm)) {
            fclose($imgStrm);
        }
        if (!$project->has_image) {
            $project->has_image = true;
            $project->save();
        }
    }

    public function delete($subject, $project)
    {
        $group = $project->group;
        $project->delete();
        $group->delete();
    }

    public function vote($subject, $project)
    {
        $user = $this->helper->getUserFromSubject($subject);

        $result = $project->voters()->toggle($user->id);
        $project->likes = $project->voters()->count();
        $project->save();
        $vote = empty($result['detached']);
        if ($vote) {
            $this->options->incrementOption('stat-votes', 1);
        } else {
            $this->options->incrementOption('stat-votes', -1);
        }
        return $vote;
    }

    public function createComment($subject, $project, $data)
    {
        $user = $this->helper->getUserFromSubject($subject);
        $schema = [
            'type' => 'object',
            'properties' => [
                'content' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 500,
                ],
            ],
            'required' => ['content'],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        $comment = $this->db->new('App:Comment');
        $comment->author_id = $user->id;
        $comment->project_id = $project->id;
        $comment->content = $data['content']; // TODO escapar emojies
        $comment->save();
        $this->options->incrementOption('stat-comments', 1);
        return $comment;
    }

    public function createReply($subject, $comment, $data)
    {
        $user = $this->helper->getUserFromSubject($subject);
        $schema = [
            'type' => 'object',
            'properties' => [
                'content' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 500,
                ],
            ],
            'required' => ['content'],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        if ($comment->parent != null) {
            $comment = $comment->parent;
        }
        $reply = $this->db->new('App:Comment');
        $reply->author_id = $user->id;
        $reply->parent_id = $comment->id;
        $reply->content = $data['content']; // TODO escapar emojies
        $reply->save();
        $this->options->incrementOption('stat-comments', 1);
        return $reply;
    }

    public function voteComment($subject, $comment, $data)
    {
        $user = $this->helper->getUserFromSubject($subject);
        $schema = [
            'type' => 'object',
            'properties' => [
                'value' => [
                    'type' => 'integer',
                    'enum' => [-1, 1],
                ],
            ],
            'required' => ['value'],
            'additionalProperties' => false,
        ];
        $v = $this->validation->fromSchema($schema);
        $v->assert($data);
        if ($comment->raters()->where('user_id', $user->id)->exists()) {
            $comment->raters()->updateExistingPivot($user->id, ['value' => $data['value']]);
        } else {
            $comment->raters()->attach($user->id, ['value' => $data['value']]);
        }
        $comment->votes = $comment->raters->sum('pivot.value');
        $comment->save();
        return $comment->votes;
    }
}
