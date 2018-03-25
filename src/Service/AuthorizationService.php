<?php
namespace App\Service;

class AuthorizationService
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function checkPermission($subject, $actName, $object = null, $proxy = null)
    {
        $action = $this->db->query('App:Action')->find('actName');
        if (is_null($action)) {
            return false;
        }
        $subRoles = $subject->getRoles();
        if (array_intersect($subRoles, $action->getAllowedRoles())) {
            return true;
        }
        if (isset($object)) {
            $objRelations = $object->getRelationsWith($subject);
            return array_intersect($objRelations, $action->getAllowedRelations());
        }
        //TODO verificar proxies
        return false;
    }
}
