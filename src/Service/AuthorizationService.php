<?php
namespace App\Service;

class AuthorizationService
{
    public function checkPermission($subject, $action, $object, $proxy)
    {
        $allowed = false;
        $subRoles = $subject->getRoles();
        $objRelations = $object->getRelationsWith($subject);
        if (array_intersect($subRoles, $action->getAllowedRoles())) {
            $allowed = true;
        } elseif (array_intersect($objRelations, $action->getAllowedRelations())) {
            $allowed = true;
        }
        //TODO verificar proxies
        return $allowed;
    }
}
