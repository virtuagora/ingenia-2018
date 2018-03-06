<?php
namespace App\Util\Subject;

class ArraySubject implements SubjectInterface
{
    protected $subject;

    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    public function getType()
    {
        return isset($this->subject['type']) ? $this->subject['type'] : 'annonymous';
    }

    public function getId()
    {
        return isset($this->subject['id']) ? $this->subject['id'] : null;
    }

    public function getRoles()
    {
        return isset($this->subject['roles']) ? $this->subject['roles'] : [];
    }

    public function toArray()
    {
        return $this->subject;
    }

    public function serialize()
    {
        return serialize($this->subject);
    }

    public function unserialize($subject) {
        $this->subject = unserialize($subject);
    }
}
