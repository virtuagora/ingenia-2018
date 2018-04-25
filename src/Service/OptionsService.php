<?php
namespace App\Service;

class OptionsService
{
    private $db;
    private $settings;

    public function __construct($db)
    {
        $this->db = $db;
        $this->settings = $db->table('options')
            ->where('autoload', true)->pluck('value', 'key');
    }

    public function getAutoloaded()
    {
        return $this->settings->toArray();
    }

    public function getOption($option)
    {
        return $this->db->query('App:Option')->where('key', $option)->first();
    }

    public function incrementOption($option, $amount)
    {
        return $this->db->table('options')->where('key', $option)->increment('value', $amount);
    }
}
