<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;

class ProjectAction
{
    protected $projectResource;
    protected $representation;
    protected $helper;
    protected $authorization;
    protected $pagination;
    
    public function __construct(
        $projectResource, $representation, $helper, $authorization, $pagination
    ) {
        $this->projectResource = $projectResource;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
        $this->pagination = $pagination;
    }
    
    // GET /proyecto/{pro}
    public function getOne($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $proyecto = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['category']
        );
        if ($this->authorization->checkPermission($subject, 'retProFull', $proyecto)) {
            $proyecto->addVisible(['notes']);
        }
        return $response->withJSON($proyecto->toArray());
    }

    public function get($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $pagParams = $this->pagination->getParams($request, [
            'loc' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'dep' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'reg' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'cat' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            's' => [
                'type' => 'string',
            ],
        ]);
        $resultados = $this->projectResource->retrieve($pagParams);
        $resultados->setUri($request->getUri());
        if ($this->authorization->checkPermission($subject, 'retDni')) {
            $resultados->makeVisible([
                'notes',
            ]);
        }
        return $this->pagination->renderResponse($response, $resultados);
        //return $response->withJSON($orgList->toArray());
    }

    public function get20Random($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $pagParams = $this->pagination->getParams($request, [
            'loc' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'dep' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'reg' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'cat' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            's' => [
                'type' => 'string',
            ],
        ]);
        $resultados = $this->projectResource->retrieveRandoms($pagParams);
        $resultados;
        $resultados->setUri($request->getUri());
        if ($this->authorization->checkPermission($subject, 'retDni')) {
            $resultados->makeVisible([
                'notes',
            ]);
        }
        return $this->pagination->renderResponse($response, $resultados);
        //return $response->withJSON($orgList->toArray());
    }

    public function getComments($request, $response, $params)
    {
        $proId = $this->helper->getSanitizedId('pro', $params);
        $pagParams = $this->pagination->getParams($request, [
            'usr' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
        ]);
        $resultados = $this->projectResource->retrieveComments($proId, $pagParams);
        $resultados->setUri($request->getUri());
        return $this->pagination->renderResponse($response, $resultados);
    }
    
    public function post($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'crePro')) {
            throw new UnauthorizedException();
        }
        $project = $this->projectResource->createOne($subject, $request->getParsedBody());
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Project created succefully',
            'status' => 200,
            'project' => $project->toArray(),
        ]);
    }

    public function patch($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
        $atributos = $request->getParsedBody();
        $project = $this->projectResource->updateOne($subject, $project, $atributos);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Información del proyecto actualizada exitosamente',
            'status' => 200,
            'project' => $project->toArray(),
        ]);
    }

    public function putNotes($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'retOpt')) {
            throw new UnauthorizedException();
        }
        $notes = $this->helper->getSanitizedStr('notes', $request->getParsedBody());
        $project->notes = $notes;
        $project->save();
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Notas del proyecto actualizada exitosamente',
            'status' => 200,
        ]);
    }

    public function delete($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'delPro', $project)) {
            throw new UnauthorizedException();
        }
        $this->projectResource->delete($subject, $project);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Proyecto y equipo eliminados exitosamente',
            'status' => 200,
        ]);
    }
    
    public function postPicture($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->projectResource->updatePicture($subject, $project, $archivo);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
    }
    
    public function postVote($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if ($subject->getType() == 'Annonymous') {
            throw new UnauthorizedException();
        }
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        $vote = $this->projectResource->vote($subject, $project);
        return $this->representation->returnMessage($request, $response, [
            'message' => $vote? '¡Proyecto bancado!': 'Proyecto ya no bancado.',
            'vote' => $vote,
            'status' => 200,
        ]);
    }
    
    public function postComment($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if ($subject->getType() != 'User') {
            throw new UnauthorizedException();
        }
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        $comment = $this->projectResource->createComment(
            $subject, $project, $request->getParsedBody()
        );
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Comentario realizado',
            'comment' => $comment,
            'status' => 200,
        ]);
    }
    
    public function postReply($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if ($subject->getType() != 'User') {
            throw new UnauthorizedException();
        }
        $comment = $this->helper->getEntityFromId(
            'App:Comment', 'com', $params
        );
        $reply = $this->projectResource->createReply(
            $subject, $comment, $request->getParsedBody()
        );
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Respuesta realizada',
            'reply' => $reply,
            'status' => 200,
        ]);
    }
    
    public function postCommentVote($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if ($subject->getType() != 'User') {
            throw new UnauthorizedException();
        }
        $comment = $this->helper->getEntityFromId(
            'App:Comment', 'com', $params
        );
        $votes = $this->projectResource->voteComment(
            $subject, $comment, $request->getParsedBody()
        );
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Comentario votado',
            'status' => 200,
            'votes' => $votes,
        ]);
    }
    
    public function showProject($request, $response, $params)
    {
        $proyecto = $this->helper->getEntityFromId(
        'App:Project', 'pro', $params
        );
        $proyecto->addVisible(['goals', 'schedule', 'budget','category_id']);
        // return $response->withJSON($proyecto->toArray());
        return $this->view->render($response, 'ingenia/project/showProject.twig', [
        'project' => $proyecto,
        ]);
    }
}