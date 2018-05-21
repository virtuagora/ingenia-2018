<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;
use Carbon\Carbon;
use Slim\Http\Stream;

class AdminAction
{
    protected $options;
    protected $representation;
    protected $helper;
    protected $authorization;
    protected $db;
    protected $filesystem;
    protected $pagination;
    
    public function __construct(
    $options, $representation, $helper, $authorization, $db, $filesystem, $pagination
    ) {
        $this->options = $options;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->db = $db;
        $this->filesystem = $filesystem;
        $this->pagination = $pagination;
    }
    
    public function getOptions($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'retOpt')) {
            throw new UnauthorizedException();
        }
        $options = $this->options->getOptions();
        return $response->withJSON($options->toArray());
    }
    
    public function getOption($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'retOpt')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        $option = $this->options->getOption($opt);
        return $response->withJSON($option->toArray());
    }
    
    public function postOption($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'retOpt')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        $val = $this->helper->getSanitizedStr('value', $request->getParsedBody());
        $option = $this->options->getOption($opt);
        $option->value = $val;
        $option->save();
        return $this->representation->returnMessage($request, $response, [
        'message' => 'Opción actualizada',
        'status' => 200,
        ]);
    }
    
    // public function getDniList($request, $response, $params)
    // {
    //     $subject = $request->getAttribute('subject');
    //     if (!$this->authorization->checkPermission($subject, 'retDni')) {
    //         throw new UnauthorizedException();
    //     }
    //     $options = $this->pagination->getParams($request, [
    //         's' => [
    //             'type' => 'string',
    //         ],
    //     ]);
    //     $query = $this->db->query('App:User')->where('verified_dni', false);
    //     if (isset($options['s'])) {
    //         $filter = $this->helper->generateTrace($options['s']);
    //         $query->where('trace', 'LIKE', "%$filter%");
    //     }
    //     $results = new Paginator($query, $options);
    //     $results->setUri($request->getUri());
    //     return $this->pagination->renderResponse($response, $results);
    // }
    
    public function postVerifiedDni($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId('App:User', 'usr', $params);
        if (!$this->authorization->checkPermission($subject, 'retDni')) {
            throw new UnauthorizedException();
        }
        $user->verified_dni = true;
        $user->save();
        $group = $user->groups()->first();
        $countMembers = $group->users()->count();
        $countVerified = $group->users()->where('verified_dni', true)->count();
        if ($countMembers >= 5 && $countMembers == $countVerified) {
            $group->verified_team = true;
            $group->save();
        }
        return $this->representation->returnMessage($request, $response, [
        'message' => 'DNI verificado',
        'status' => 200,
        ]);
    }
    
    public function getMatriz($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $opt = $this->helper->getSanitizedStr('opt', $params);
        if (!in_array($opt, ['proyectos', 'equipos'])) {
            throw new AppException('No existe el dataset solicitado', 404);
        }
        $writer = \Box\Spout\Writer\WriterFactory::create(\Box\Spout\Common\Type::XLSX);
        $path = $opt.'.xlsx';
        if ($this->filesystem->has($path)) {
            $updDate = Carbon::createFromTimestamp($this->filesystem->getTimestamp($path));
            $expLimit = new Carbon('+ 4 hours');
            if ($updDate->gt($expLimit)) {
                return $response
                ->withBody(new Stream($this->filesystem->readStream($path)))
                ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            } else {
                $this->filesystem->delete($path);
            }
        }
        $this->filesystem->copy('sample.xlsx', $path);
        $tmpHandle = $this->filesystem->readStream($path);
        $metaDatas = stream_get_meta_data($tmpHandle);
        $tmpFilename = $metaDatas['uri'];
        $defStyle = (new \Box\Spout\Writer\Style\StyleBuilder())->setShouldWrapText()->build();
        $writer->openToFile($tmpFilename);
        
        if ($opt == 'proyectos') {
            $grupos = $this->db->query('App:Group', [
            'users', 'locality.department', 'project.locality.department'
            ])->get();
            $writer->addRow([
            'ID', 'Nombre del equipo', 'Acerca del equipo', 'Región',
            'Departamento', 'Localidad',
            'Año de conformación', 'Participación en ediciones anteriores',
            'Email', 'Teléfono', 'Web', 'Facebook',
            'Organización que pertenece el equipo',
            'Nombre del proyecto', 'Resumen', 'Fundamentación', 'Temática',
            'Trabajo ya ejecutado', 'Región del proyecto',
            'Departamento del proyecto', 'Localidad del proyecto',
            'Barrios', 'Monto total solicitado',
            'Organización con la que realizará las actividades',
            'Cantidad de integrantes', '¿Equipo completo?', '¿Responsable asignado?',
            '¿DNI validados de todo el equipo?', '¿Cartas cargadas?',
            '¿Cargó imagen?', 'Puntaje', 'Observaciones',
            'Condición',
            ]);
            foreach ($grupos as $gro) {
                if ($gro->project) {
                    $pro = $gro->project;
                } else {
                    $pro = (object) [
                    'name' => null, 'abstract' => null, 'foundation' => null, 'category' => null,
                    'previous_work' => null, 'locality' => (object) [
                    'name' => null, 'custom' => null, 'department' => (object) [
                    'name' => null,
                    'region_id' => null,
                    ],
                    ],
                    'neighbourhoods' => [], 'total_budget' => null, 'organization' => null,
                    'has_image' => null, 'notes' => null, 'selected' => null,
                    ];
                }
                $writer->addRowWithStyle([
                $gro->id, $gro->name, $gro->description, $gro->locality->department->region_id,
                $gro->locality->department->name, $gro->locality->custom? $gro->locality_other: $gro->locality->name,
                $gro->year, implode(', ', $gro->previous_editions),
                $gro->email, $gro->telephone, $gro->web, $gro->facebook,
                $gro->parent_organization? $gro->parent_organization['name']: null,
                $pro->name, $pro->abstract, $pro->foundation, $pro->category? $pro->category->name: null,
                $pro->previous_work, $pro->locality->department->region_id,
                $pro->locality->department->name, $pro->locality->custom? $pro->locality_other: $pro->locality->name,
                implode(', ', $pro->neighbourhoods), $pro->total_budget,
                $pro->organization? $pro->organization['name']: null,
                $gro->users->count(), $gro->full_team? 'SI': 'NO', $gro->second_in_charge? 'SI': 'NO',
                $gro->verified_team? 'SI': 'NO', ($gro->uploaded_agreement && $gro->uploaded_letter)? 'SI': 'NO',
                $pro->has_image? 'SI': 'NO', $gro->quota, $pro->notes,
                $pro->selected? 'Seleccionado': null,
                ], $defStyle);
            }
        } elseif ($opt == 'equipos') {
            $grupos = $this->db->query('App:Group', [
            'users.locality.department',
            ])->get();
            $writer->addRow([
            'ID del equipo', 'Nombre del equipo', 'Cargo en equipo',
            'DNI', 'Nombre/s', 'Apellidos/s',
            'Región',
            'Departamento',
            'Localidad',
            'Barrio', 'Dirección',
            'Fecha de nacimiento', 'Edad',
            'Género', 'Teléfono', 'Email', 'Facebook'
            ]);
            foreach ($grupos as $gro) {
                foreach ($gro->users as $usr) {
                    $cumple = Carbon::parse($usr->birthday);
                    $writer->addRowWithStyle([
                    $gro->id, $gro->name, $usr->pivot->relation,
                    $usr->dni, $usr->names, $usr->surnames,
                    $usr->locality->department->region_id,
                    $usr->locality->department->name,
                    $usr->locality->custom? $usr->locality_other: $usr->locality->name,
                    $usr->neighbourhood, $usr->address,
                    $cumple->toDateString(), $cumple->age,
                    $usr->gender, $usr->telephone, $usr->email, $usr->facebook,
                    ], $defStyle);
                }
            }
        }
        
        $writer->close();
        return $response
        ->withBody(new Stream($tmpHandle))
        ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->withHeader('Content-Disposition', 'attachment; filename='.$path);
    }
}