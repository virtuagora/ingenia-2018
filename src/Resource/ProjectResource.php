<?php

namespace App\Resource;

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
                    'maxLength' => 1000,
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
                            'required' => ['date', 'activity'],
                            'additionalProperties' => false,
                        ],
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
                                'type' => 'integer',
                                'minimum' => 1,
                                'minimum' => 300,
                            ],
                            'amount' => [
                                'type' => 'number',
                                'minimum' => 1,
                            ],
                            'required' => ['category', 'description', 'amount'],
                            'additionalProperties' => false,
                        ],
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
                                'required' => ['name', 'topics', 'locality_id'],
                                'additionalProperties' => false,
                            ],
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
                'neighbourhood', 'goals', 'schedule', 'budget', 'organization',
                'locality_id', 'locality_other',
            ],
            'additionalProperties' => false,
        ];
        return $schema;
    }

    public function createOne($subject, $data)
    {
        $v = $this->validation->fromSchema($this->retrieveSchema());
        $v->assert($data);

        $user = $this->helper->getUserFromSubject($subject);
        $group = $user->groups()->with('project')->wherePivot('relation', 'responsable')->first();

        if (is_null($group)) {
            throw new AppException('Debe cargar un equipo primero');
        }
        if (is_null($group->project)) {
            throw new AppException('El equipo ya posee un proyecto cargado');
        }

        $localidad = $this->db->query('App:Locality')->findOrFail($data['locality_id']);
        $categoria = $this->db->query('App:Category')->findOrFail($data['category_id']);

        // calcular limite del presupuesto

        $project = $this->db->new('App:Project');
        $project->name = $data['name'];
        $project->abstract = $data['abstract'];
        $project->foundation = $data['foundation'];
        $project->previous_work = $data['previous_work'];
        $project->neighbourhoods = $data['neighbourhoods'];
        $project->goals = $data['goals'];
        $project->budget = $data['budget'];
        $project->schedule = $data['schedule'];
        $project->organization = $data['organization'];
        $project->category_id = $data['category_id'];
        $project->locality_id = $data['locality_id'];
        if ($localidad->custom && isset($data['locality_other'])) {
            $project->locality_other = $data['locality_other'];
        }
        $project->group_id = $group->id;
        $project->save();
        return $project;
    }
}
