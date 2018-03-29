<?php
namespace App\Service;

class OptionsService
{
    private $db;
    private $settings;

    public function __construct($db)
    {
        $this->db = $db;
        $this->settings = $db->table('options')->where('autoload', 'true')->get()->keyBy('key');
    }

    public function getAutoloaded()
    {
        return $this->settings;
    }

    public function getOption($option)
    {
        return $this->db->query('App:Option')->where('key', $option)->first();
    }
}
