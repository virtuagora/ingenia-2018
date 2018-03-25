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
                'nombre' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'resumen' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 1500,
                ],
                'fundamentacion' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 1000,
                ],
                'categoria' => [
                    'type' => 'integer',
                    'minimum' => 1,
                ],
                'descripcionEjecucion' => [
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
                'barrios' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 500,
                ],
                'objetivos' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 1000,
                ],
                'actividades' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'day' => [
                                'type' => 'integer',
                                'minimum' => 1,
                                'minimum' => 31,
                            ],
                            'month' => [
                                'type' => 'integer',
                                'minimum' => 1,
                                'minimum' => 12,
                            ],
                            'activity' => [
                                'type' => 'string',
                                'minLength' => 1,
                                'maxLength' => 250,
                            ],
                            'required' => ['day', 'month', 'activity'],
                            'additionalProperties' => false,
                        ],
                    ],
                ],
                'presupuesto' => [
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
                                'minimum' => 250,
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
                'organizacion' => [
                    'oneOf' => [
                        [
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'minLength' => 1,
                                    'maxLength' => 50,
                                ],
                                'category_id' => [
                                    'type' => 'integer',
                                    'minimum' => 1,
                                ],
                                'locality_id' => [
                                    'type' => 'integer',
                                    'minimum' => 1,
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
                                'required' => ['name', 'category_id', 'locality_id'],
                                'additionalProperties' => false,
                            ],
                        ], [
                            'type' => 'null',
                        ],
                    ],
                ],

                // TODO localidad_id y si es otra incluir localidad_otra
                // nodo y departamento no me importan
                'localidad' => [
                    'type' => 'integer',
                    'minimum' => 1,
                ],
                'localidad_otra' => [
                    'type' => 'string',
                    'minLength' => 1,
                    'maxLength' => 250,
                ],
                /// /// /// /// ///
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

        $localidad = $this->db->query('App:Locality')->findOrFail($data['localidad']);
        $categoria = $this->db->query('App:Category')->findOrFail($data['categoria']);

        $project = $this->db->new('App:Project');
        $project->name = $data['nombre'];
        $project->abstract = $data['resumen'];
        $project->foundation = $data['fundamentacion'];
        $project->previous_work = $data['descripcionEjecucion'];
        $project->neighbourhood = $data['barrios'];
        $project->goals = $data['objetivos'];
        $project->budget = $data['presupuesto'];
        $project->schedule = $data['actividades'];
        $project->organization = $data['organizacion'];
        $project->category_id = $data['categoria'];
        $project->locality_id = $data['localidad'];
        if ($localidad->custom && isset($data['localidad_otra'])) {
            $project->locality_other = $data['localidad_otra'];
        }
        $project->save();
        return $project;
    }
}
