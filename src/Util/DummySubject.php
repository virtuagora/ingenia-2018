<?php
namespace App\Util;

class DummySubject
{
    protected $id;
    protected $type;
    protected $name;
    protected $roles;

    public function __construct($type, $id = null, $name = null, $roles = null)
    {
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'roles' => $this->roles,
        ];
    }

    public function retrieveSubject($with = null)
    {
        return $this->db->query('App:Subject', $with)->findOrFail($this->id);
    }
}
