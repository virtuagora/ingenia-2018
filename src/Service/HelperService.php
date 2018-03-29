<?php

namespace App\Service;

use App\Util\DummySubject;

class HelperService
{
    private $db;
    private $logger;
    
    public function __construct($db, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getUserFromSubject(DummySubject $subject, $with = null)
    {
        return $this->db->query('App:User', $with)
            ->where('subject_id', $subject->getId())
            ->firstOrFail();
    }

    public function getEntityFromId($model, $key, $params = null, $with = null)
    {
        $id = isset($params)? $this->getSanitizedId($key, $params): $key;
        return $this->db->query($model, $with)->findOrFail($id);
    }

    public function getSanitizedId($attr, $params)
    {
        $isDigit = ctype_digit($params[$attr] ?? 'x');
        return $isDigit ? $params[$attr] : -1;
    }
}
