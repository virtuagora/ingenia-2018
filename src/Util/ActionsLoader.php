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
            //['id' => 'retUsrInvit', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'retOpt', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'retDni', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'retMat', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'retUsrFull', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'updUsrPas', 'group' => 'user', 'allowed_roles' => '[]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'updUsrProfile', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'updUsrDni', 'group' => 'user', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["self"]', 'allowed_proxies' => '[]'],
            ['id' => 'retGroFull', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["miembro","co-responsable","responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'retProFull', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["miembro","co-responsable","responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'crePro', 'group' => 'project', 'allowed_roles' => '["admin","user"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'creGro', 'group' => 'project', 'allowed_roles' => '["admin","user"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroInvReq', 'group' => 'project', 'allowed_roles' => '["admin","user"]', 'allowed_relations' => '[]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroInvit', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroCompleted', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGroLetter', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGroAgreement', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGroSecond', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroUsrInv', 'group' => 'invitation', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["receiver"]', 'allowed_proxies' => '[]'],
            ['id' => 'creGroUsrReq', 'group' => 'invitation', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["receiver"]', 'allowed_proxies' => '[]'],
            ['id' => 'updGro', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'updPro', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
            ['id' => 'delPro', 'group' => 'project', 'allowed_roles' => '["admin"]', 'allowed_relations' => '["responsable"]', 'allowed_proxies' => '[]'],
        ]);
    }
}
