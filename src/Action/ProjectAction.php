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
        return $response->withJSON($proyecto->toArray());
    }

    public function get($request, $response, $params)
    {
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
        return $this->pagination->renderResponse($response, $resultados);
        //return $response->withJSON($orgList->toArray());
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
    
    public function postComment($req, $res, $arg)
    {
        if (!$this->session->has('user')) {
            throw new \App\Util\AppException('Necesitás identificarte para realizar esta acción.', 403);
        }
        $userID = $this->session->get('user.id');
        $project = $this->db->query('App:Project')->findOrFail($arg['pro']);
        $params = $req->getParsedBody();
        if (!$this->validator['comment']->validate($params)) {
            throw new \App\Util\AppException('Parametros invalidos', 400);
        }
        $comment = new \App\Model\Comment();
        $comment->user_id = $userID;
        $comment->project_id = $project->id;
        $comment->content = $this->emojione->toShort($params['content']);
        $comment->save();
        $this->db->table('options')->where('key', 'comentarios')->increment('value');
        return $res->withJSON([
        'mensaje' => 'Comentario realizado.',
        'id' => $comment->id,
        ]);
    }
    
    public function postReply($req, $res, $arg)
    {
        if (!$this->session->has('user')) {
            throw new \App\Util\AppException('Necesitás identificarte para realizar esta acción.', 403);
        }
        $userID = $this->session->get('user.id');
        $parent = $this->db->query('App:Comment')->findOrFail($arg['com']);
        if ($parent->parent != null) {
            $parent = $parent->parent;
        }
        $params = $req->getParsedBody();
        if (!$this->validator['comment']->validate($params)) {
            throw new \App\Util\AppException('Parametros invalidos', 400);
        }
        $comment = new \App\Model\Comment();
        $comment->user_id = $userID;
        $comment->parent_id = $parent->id;
        $comment->content = $this->emojione->toShort($params['content']);
        $comment->save();
        $this->db->table('options')->where('key', 'comentarios')->increment('value');
        return $res->withJSON([
        'mensaje' => 'Comentario respondido.',
        'id' => $comment->id,
        ]);
    }
    
    public function postCommentVote($req, $res, $arg)
    {
        if (!$this->session->has('user')) {
            throw new \App\Util\AppException('Necesitás identificarte para realizar esta acción.', 403);
        }
        $userID = $this->session->get('user.id');
        $comment = $this->db->query('App:Comment')->findOrFail($arg['com']);
        $params = $req->getParsedBody();
        if (!$this->validator['rate']->validate($params)) {
            throw new \App\Util\AppException('Tu comentario es muy largo o muy corto.', 400);
        }
        if ($comment->raters()->where('user_id', $userID)->exists()) {
            $comment->raters()->updateExistingPivot($userID, ['value' => $params['value']]);
        } else {
            $comment->raters()->attach($userID, ['value' => $params['value']]);
        }
        $comment->votes = $comment->raters->sum('pivot.value');
        $comment->save();
        return $res->withJSON([
        'mensaje' => 'Comentario votado.',
        'likes' => $comment->votes,
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