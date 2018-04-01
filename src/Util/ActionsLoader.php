<?php namespace App\Util;

class ActionsLoader
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function down()
    {
        $this->db->table('actions')->delete();
    }

    public function up()
    {
        $this->db->table('actions')->insert([
            ['id' => 'updUsrProfile', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'updUsrDni', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'crePro', 'group' => 'project', 'allowed_roles' => '["admin","user"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'creGro', 'group' => 'project', 'allowed_roles' => '["admin","user"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroInvit', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGroLetter', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGroAgreement', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
        ]);
    }
}
