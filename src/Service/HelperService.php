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
}
