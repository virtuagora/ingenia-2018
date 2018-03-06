<?php
namespace App\Util\Subject;

use Serializable;

interface SubjectInterface
{
    public function getType();

    public function getId();

    public function getRoles();

    public function toArray();
}
