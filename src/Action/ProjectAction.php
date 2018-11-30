<?php

namespace App\Action;

use App\Util\Exception\AppException;
use App\Util\Exception\UnauthorizedException;
use Slim\Http\Stream;

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
            'cor' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'cat' => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            'sel' => [
                'type' => 'boolean',
            ],
            's' => [
                'type' => 'string',
            ],
        ]);
        $resultados = $this->projectResource->retrieve($pagParams);
        $resultados->setUri($request->getUri());
        if ($this->authorization->checkPermission($subject, 'coordin')) {
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
            'sel' => [
                'type' => 'boolean',
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
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
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
            'message' => $vote ? '¡Proyecto bancado!' : 'Proyecto ya no bancado.',
            'vote' => $vote,
            'status' => 200,
        ]);
    }

    public function postCoordin($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        $user = $this->helper->getUserFromSubject($subject);
        $project->coordin_id = $user->id;
        $project->save();
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Proyecto asignado',
            'status' => 200,
        ]);
    }

    public function deleteCoordin($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params
        );
        $project->coordin_id = null;
        $project->save();
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Coordinador desvinculado',
            'status' => 200,
        ]);
    }

    public function postReview($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
        $this->projectResource->updateReview(
            $subject, $project, $request->getParsedBody()
        );
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Evaluación del proyecto actualizada',
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
        $proyecto->addVisible(['goals', 'schedule', 'budget', 'category_id']);
        // return $response->withJSON($proyecto->toArray());
        return $this->view->render($response, 'ingenia/project/showProject.twig', [
            'project' => $proyecto,
        ]);
    }

    // ==========

    public function getStories($request, $response, $params)
    {
        $proId = $this->helper->getSanitizedId('pro', $params);
        $pagParams = $this->pagination->getParams($request, [
            // 'usr' => [
            //     'type' => 'integer',
            //     'minimum' => 1,
            // ],
        ]);
        $resultados = $this->projectResource->retrieveStories($proId, $pagParams);
        $resultados->setUri($request->getUri());
        return $this->pagination->renderResponse($response, $resultados);
    }

    public function getAllStories($request, $response, $params)
    {
        $pagParams = $this->pagination->getParams($request, [
        ]);
        $resultados = $this->projectResource->retrieveAllStories($pagParams);
        $resultados->setUri($request->getUri());
        return $this->pagination->renderResponse($response, $resultados);
    }

    public function postStory($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $group = $this->helper->getEntityFromId(
            'App:Group', 'gro', $params, []
        );
        if (!$this->authorization->checkPermission($subject, 'retGroFull', $group)) {
            throw new UnauthorizedException();
        }
        if (is_null($group->project)) {
            throw new AppException('El grupo no puede acceder al recurso');
        }
        if ($group->project->selected == false) {
            throw new AppException('El proyecto no puede subir historias');
        }
        // BODY
        $data = $request->getParsedBody();
        // IMAGE
        $imgFile = $request->getUploadedFiles()['post-cover'];

        $story = $this->projectResource->createStory($subject, $group->project, $data, $imgFile);
        $url = $this->helper->pathFor('showHistoria', true, [
            'story' => $story->id,
        ]);
        return $response->withRedirect($url);
    }

    public function deleteStory($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $story = $this->helper->getEntityFromId(
            'App:Story', 'sto', $params, ['project']
        );
        $project = $story->project;
        if (!$this->authorization->checkPermission($subject, 'retGroFull', $project)) {
            throw new UnauthorizedException();
        }
        $this->projectResource->deleteStory($subject, $story, $project);
        $url = $this->helper->pathFor('showProject', true, [
            'pro' => $project->id,
        ]);
        return $response->withRedirect($url);
    }

    public function postReceipt($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
        if (!$project->selected) {
            throw new UnauthorizedException();
        }
        if (empty($request->getUploadedFiles()['archivo'])) {
            throw new AppException('No se envió un archivo');
        }
        $data = $request->getParsedBody();
        $archivo = $request->getUploadedFiles()['archivo'];
        $this->projectResource->createReceipt($subject, $project, $data, $archivo);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
    }

     public function postSendReceipts($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
        if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $this->projectResource->setReceiptsSent($subject, $project);
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));

    }

     public function postAdminApproveReceipts($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'coordin', $project)) {
            throw new UnauthorizedException();
        }
        if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $project->budget_approved = true;
        $project->save();
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));

    }

     public function postAdminRejectReceipts($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'coordin', $project)) {
            throw new UnauthorizedException();
        }
        if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $project->budget_sent = false;
        $project->budget_approved = false;
        $project->save();
        return $response->withRedirect($request->getHeaderLine('HTTP_REFERER'));
    }

    public function getAllReceipts($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
         if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $pagParams = $this->pagination->getParams($request, [
        ]);
        $resultados = $this->projectResource->retrieveAllReceipts($project->id, $pagParams);
        $resultados->setUri($request->getUri());
        return $this->pagination->renderResponse($response, $resultados);
    }

    public function getReceipt($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $receipt = $this->helper->getEntityFromId(
            'App:Receipt', 'rec', $params, ['project']
        );
        $project = $receipt->project;
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
         if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $fileData = $this->projectResource->getReceipt($project, $receipt);
        return $response->withBody(new Stream($fileData['strm']))
        ->withHeader('Content-Type', $fileData['mime']);
    }

    public function getAdminReceipt($request, $response, $params)
    {
        $receiptId = $this->helper->getSanitizedId('rec', $params);
        $subject = $request->getAttribute('subject');
        $project = $this->helper->getEntityFromId(
            'App:Project', 'pro', $params, ['group']
        );
        if (!$this->authorization->checkPermission($subject, 'coordin')) {
            throw new UnauthorizedException();
        }
         if (!$project->selected) {
            throw new UnauthorizedException();
        }
        $receipt = $this->helper->getEntityFromId(
            'App:Receipt', 'rec', $params, ['project']
        );
        $fileData = $this->projectResource->getReceipt($project, $receipt);
        return $response->withBody(new Stream($fileData['strm']))
        ->withHeader('Content-Type', $fileData['mime']);
    }

     public function deleteReceipt($request, $response, $params)
    {
        $subject = $request->getAttribute('subject');
        $receipt = $this->helper->getEntityFromId(
            'App:Receipt', 'rec', $params, ['project']
        );
        $project = $receipt->project;
        if (!$this->authorization->checkPermission($subject, 'updPro', $project)) {
            throw new UnauthorizedException();
        }
        $this->projectResource->deleteReceipt($subject, $receipt, $project);
        return $this->representation->returnMessage($request, $response, [
            'message' => 'Recibo borrado',
            'status' => 200,
        ]);
    }

}
