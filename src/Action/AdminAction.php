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

    public function __construct($options, $representation, $helper, $authorization, $db, $filesystem)
    {
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
