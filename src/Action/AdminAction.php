<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class AdminAction
{
    protected $options;
    protected $representation;
    protected $helper;
    protected $authorization;
    protected $db;
    protected $filesystem;

    public function __construct(
        $options, $representation, $helper, $authorization, $db, $filesystem, $pagination
    ) {
        $this->options = $options;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->db = $db;
        $this->filesystem = $filesystem;
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

    public function getDniList($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'retDni')) {
            throw new UnauthorizedException();
        }
        $options = $this->pagination->getParams($request, [
            's' => [
                'type' => 'string',
            ],
        ]);
        $query = $this->db->query('App:User')->where('verified_dni', false);
        if (isset($options['s'])) {
            $filter = $this->helper->generateTrace($options['s']);
            $query->where('trace', 'LIKE', "%$filter%");
        }
        $results = new Paginator($query, $options);
        $results->setUri($request->getUri());
        return $this->pagination->renderResponse($response, $results);
    }

    public function postVerifiedDni($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $user = $this->helper->getEntityFromId('App:User', 'usr', $params);
        if (!$this->authorization->checkPermission($subject, 'retDni')) {
            throw new UnauthorizedException();
        }
        $user->verified_dni = true;
        $user->save();
        return $this->representation->returnMessage($request, $response, [
            'message' => 'DNI verificado',
            'status' => 200,
        ]);
    }

    public function getMatriz($request, $response, $params)
    {
        $proyectos = $this->db->query('App:Project', ['group'])->get();
        $writer = \Box\Spout\Writer\WriterFactory::create(\Box\Spout\Common\Type::XLSX);
        $this->filesystem->addPlugin(new \League\Flysystem\Plugin\ForcedCopy);
        $this->filesystem->forceCopy('sample.xlsx', 'temp.xlsx');
        $tmpHandle = $this->filesystem->readStream('temp.xlsx');
        $metaDatas = stream_get_meta_data($tmpHandle);
        $tmpFilename = $metaDatas['uri'];
        $writer->openToFile($tmpFilename);
        $writer->addRow(['ID', 'Proyecto', 'Equipo', 'Descripcion']);
        foreach ($proyectos as $pro) {
            $writer->addRow([$pro->id, $pro->name, $pro->group->name, $pro->abstract]);
        }
        $writer->close();
        //fclose($tmpHandle);
        return $response
            ->withBody(new \Slim\Http\Stream($tmpHandle))
            ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
