<?php namespace App\Action;

final class TestingController extends AbstractController
{
    
    public function commentNodeAction($request, $response, $params)
    {
        return $res->withJSON([
        'mgs' => 'Perfecto!'
        ]);
    }
    
}