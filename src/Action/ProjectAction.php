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

    public function __construct($projectResource, $representation, $helper, $authorization)
    {
        $this->projectResource = $projectResource;
        $this->representation = $representation;
        $this->helper = $helper;
        $this->authorization = $authorization;
    }

    // GET /proyecto/{pro}
    public function getOne($request, $response, $params)
    {
        $proyecto = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        return $response->withJSON($proyecto->toArray());
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

    public function postPicture($req, $res, $arg)
    {
        if (!$this->session->has('user')) {
            throw new \App\Util\AppException('Necesitás identificarte para realizar esta acción.', 403);
        }
        $project = $this->db->query('App:Project')->findOrFail($arg['pro']);
        $user = $project->user;
        if ($user->id != $this->session->get('user.id')) {
            throw new \App\Util\AppException('Acceso denegado.', 405);
        }
        $files = $req->getUploadedFiles();
        if (empty($files['imagen'])) {
            throw new \App\Util\AppException('No se envió ninguna imagen.', 400);
        }
        $imgFile = $files['imagen'];
        if ($imgFile->getError() == UPLOAD_ERR_NO_FILE) {
            return $res->withRedirect(
                $this->helper->completePathFor('proViewGet', ['pro' => $project->id])
            );
        } elseif ($imgFile->getError() !== UPLOAD_ERR_OK) {
            throw new \App\Util\AppException(
                'Hubo un error con la imagen recibida ('.$imgFile->getError().')',
                400
            );
        }
        $imgStrm = $this->image->make($imgFile->getStream()->detach())
            ->fit(800, 565, function ($constraint) {
                $constraint->upsize();
            })->encode('jpg', 75);
        $this->filesystem->put('project/'.$project->id.'.jpg', $imgStrm);
        if (is_resource($imgStrm)) {
            fclose($imgStrm);
        }
        if (!$project->has_image) {
            $project->has_image = true;
            $project->save();
        }
        return $res->withRedirect(
            $this->helper->completePathFor('proViewGet', ['pro' => $project->id])
        );
    }

    public function postVote($req, $res, $arg)
    {
        if (!$this->session->has('user')) {
            throw new \App\Util\AppException('Necesitás identificarte para realizar esta acción.', 403);
        }
        $userID = $this->session->get('user.id');
        $project = $this->db->query('App:Project')->findOrFail($arg['pro']);
        $result = $project->voters()->toggle($userID);
        $project->likes = $project->voters()->count();
        $project->save();
        $vote = empty($result['detached']);
        if ($vote) {
            $this->db->table('options')->where('key', 'votos')->increment('value');
        } else {
            $this->db->table('options')->where('key', 'votos')->decrement('value');
        }
        return $res->withJSON([
            'mensaje' => $vote? '¡Proyecto bancado!': 'Proyecto ya no bancado.',
            'vote' => $vote,
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
}
