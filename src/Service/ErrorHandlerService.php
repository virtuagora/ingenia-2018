<?php

namespace App\Service;

use App\Utils\SujetoDummy;

class ErrorHandlerService
{
    protected $logger;
    protected $exceptions;
    
    public function __construct(array $exceptions, $logger = null)
    {
        $this->logger = $logger;
        $this->exceptions = $exceptions;
    }
    
    public function __invoke($request, $response, $exception)
    {
        if (isset($this->logger)) {
            $this->logger->info(
            $exception->getMessage().
            ' ['.$exception->getFile().' - '.$exception->getLine().']'
            );
            //$this->logger->info($request->getHeaderLine('Accept'));
            //$this->logger->info(json_encode($request->getAttributes()));
        }
        foreach($this->exceptions as $trigger => $handler) {
            if ($exception instanceof $trigger) {
                return call_user_func($handler, $response, $exception);
            }
        }
        return $response->withStatus(500)->withJSON([
        'mensaje' => 'Error interno',
        // TODO Ocultar en produccion
        'log' =>  str_replace('"',"'",$exception->getMessage()),
        'file' =>  $exception->getFile(),
        'line' =>  $exception->getLine(),
        
        'request' => $request->getAttributes(),
        ]);
    }
}